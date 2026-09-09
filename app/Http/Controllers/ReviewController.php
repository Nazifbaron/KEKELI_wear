<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /*
    |----------------------------------------------------------
    | Soumission d'un avis client depuis le formulaire front.
    | L'avis est sauvegardé avec is_approved = false.
    | L'admin le valide depuis /admin/reviews.
    |----------------------------------------------------------
    */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:60',
            'city'       => 'nullable|string|max:80',
            'rating'     => 'required|integer|min:1|max:5',
            'content'    => 'required|string|min:10|max:600',
        ]);

        Review::create(array_merge($validated, [
            'is_approved' => false, // En attente de modération admin
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Merci pour votre avis ! Il sera publié après validation.',
        ]);
    }
}
