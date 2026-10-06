<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /*
    |----------------------------------------------------------
    | Upload images supplémentaires — max 6 par produit
    |----------------------------------------------------------
    */
    public function upload(Request $request, Product $product)
    {
        $currentCount = $product->extraImages()->count();
        $remaining    = 6 - $currentCount;

        if ($remaining <= 0) {
            return back()->with('error', 'Maximum 6 images atteint pour ce produit.');
        }

        $request->validate([
            'images'   => 'required|array|max:' . $remaining,
            'images.*' => 'image|max:5120',
        ]);

        $order = $currentCount;
        foreach ($request->file('images') as $file) {
            if ($order >= 6) break;
            ProductImage::create([
                'product_id' => $product->id,
                'path'       => $file->store('products', 'public'),
                'order'      => $order++,
            ]);
        }

        return back()->with('success', '✓ ' . count($request->file('images')) . ' image(s) ajoutée(s).');
    }

    /*
    |----------------------------------------------------------
    | Supprimer une image supplémentaire
    |----------------------------------------------------------
    */
    public function delete(ProductImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return back()->with('success', 'Image supprimée.');
    }
}
