<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    /* INDEX */
    public function index()
    {
        $slides = HeroSlide::orderBy('order')->get();
        return view('admin.hero-slides.index', compact('slides'));
    }
    /* STORE */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tag'                 => 'required|string|max:60',
            'title'               => 'required|string|max:120',
            'title_highlight'     => 'nullable|string|max:80',
            'subtitle'            => 'nullable|string|max:300',
            'btn_primary_label'   => 'nullable|string|max:60',
            'btn_primary_url'     => 'nullable|string|max:200',
            'btn_secondary_label' => 'nullable|string|max:60',
            'btn_secondary_url'   => 'nullable|string|max:200',
            'overlay_color'       => 'in:red,blue,purple',
            'order'               => 'integer|min:0',
            'is_active'           => 'boolean',
            'image'               => 'nullable|image|max:8192',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hero', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        HeroSlide::create($data);

        return redirect()->route('admin.hero-slides')
                         ->with('success', '✦ Slide "' . $data['tag'] . '" créée.');
    }
    
    /* UPDATE — méthode manquante jusqu'ici */
    public function update(Request $request, HeroSlide $slide)
    {
        $data = $request->validate([
            'tag'                 => 'required|string|max:60',
            'title'               => 'required|string|max:120',
            'title_highlight'     => 'nullable|string|max:80',
            'subtitle'            => 'nullable|string|max:300',
            'btn_primary_label'   => 'nullable|string|max:60',
            'btn_primary_url'     => 'nullable|string|max:200',
            'btn_secondary_label' => 'nullable|string|max:60',
            'btn_secondary_url'   => 'nullable|string|max:200',
            'overlay_color'       => 'in:red,blue,purple',
            'order'               => 'integer|min:0',
            'is_active'           => 'boolean',
            'image'               => 'nullable|image|max:8192',
        ]);

        /* Nouvelle image → supprimer l'ancienne */
        if ($request->hasFile('image')) {
            if ($slide->image) Storage::disk('public')->delete($slide->image);
            $data['image'] = $request->file('image')->store('hero', 'public');
        } else {
            unset($data['image']); /* Garder l'image existante */
        }

        $data['is_active'] = $request->boolean('is_active');
        $slide->update($data);

        return redirect()->route('admin.hero-slides')
                         ->with('success', '✦ Slide "' . $slide->tag . '" mise à jour.');
    }

    /* TOGGLE */
    public function toggle(HeroSlide $slide)
    {
        $slide->update(['is_active' => !$slide->is_active]);
        $state = $slide->is_active ? 'activée' : 'désactivée';
        return back()->with('success', 'Slide ' . $state . '.');
    }

    /* DESTROY */
    public function destroy(HeroSlide $slide)
    {
        if ($slide->image) Storage::disk('public')->delete($slide->image);
        $slide->delete();
        return back()->with('success', 'Slide supprimée.');
    }
}
