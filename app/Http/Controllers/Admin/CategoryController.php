<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /* ============================================================
       INDEX — liste paginée de toutes les catégories
    ============================================================ */
    public function index()
    {
        $categories = Category::withCount([
            'products as product_count' => fn($q) => $q->where('is_active', true)
        ])
        ->orderBy('order')
        ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    /* ============================================================
       STORE — créer un nouvel univers
    ============================================================ */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:80',
            'slug'        => 'required|string|max:80|unique:categories,slug|regex:/^[a-z0-9-]+$/',
            'description' => 'nullable|string|max:300',
            'icon'        => 'nullable|string|max:4',
            'color'       => 'nullable|string|max:7',
            'order'       => 'nullable|integer|min:1|max:99',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['order']     = $data['order'] ?? (Category::max('order') + 1);

        Category::create($data);

        return redirect()->route('admin.categories')
                         ->with('success', '✦ Univers "' . $data['name'] . '" créé avec succès.');
    }

    /* ============================================================
       UPDATE — modifier un univers existant
    ============================================================ */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:80',
            'slug'         => 'required|string|max:80|regex:/^[a-z0-9-]+$/|unique:categories,slug,' . $category->id,
            'description'  => 'nullable|string|max:300',
            'icon'         => 'nullable|string|max:4',
            'color'        => 'nullable|string|max:7',
            'order'        => 'nullable|integer|min:1|max:99',
            'is_active'    => 'boolean',
            'image'        => 'nullable|image|max:5120',
            'remove_image' => 'nullable|boolean',
        ]);

        /* Gestion image */
        if ($request->hasFile('image')) {
            if ($category->image) Storage::disk('public')->delete($category->image);
            $data['image'] = $request->file('image')->store('categories', 'public');
        } elseif ($request->boolean('remove_image') && $category->image) {
            Storage::disk('public')->delete($category->image);
            $data['image'] = null;
        } else {
            unset($data['image']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);

        return redirect()->route('admin.categories')
                         ->with('success', '✦ Univers "' . $category->name . '" mis à jour.');
    }

    /* ============================================================
       TOGGLE — activer / désactiver rapidement
    ============================================================ */
    public function toggle(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        $state = $category->is_active ? 'activé' : 'désactivé';
        return back()->with('success', 'Univers "' . $category->name . '" ' . $state . '.');
    }

    /* ============================================================
       DESTROY — supprimer un univers (uniquement si aucun produit)
    ============================================================ */
    public function destroy(Category $category)
    {
        /*
        | Sécurité : on ne peut pas supprimer un univers
        | qui contient encore des produits actifs ou inactifs.
        | L'admin doit d'abord déplacer ou supprimer les produits.
        */
        $productCount = $category->products()->count();
        if ($productCount > 0) {
            return back()->with('error',
                'Impossible de supprimer "' . $category->name . '" — ' .
                $productCount . ' produit(s) y sont rattachés. ' .
                'Déplacez-les d\'abord vers un autre univers.'
            );
        }

        /* Supprimer l'image si elle existe */
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories')
                         ->with('success', 'Univers "' . $name . '" supprimé.');
    }
}
