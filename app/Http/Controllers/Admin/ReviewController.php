<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /* ============================================================
       INDEX — liste avec filtres et compteurs
    ============================================================ */
    public function index(Request $request)
    {
        $query = Review::latest();

        /* Filtre rapide */
        if ($request->filter === 'pending') {
            $query->where('is_approved', false);
        } elseif ($request->filter === 'approved') {
            $query->where('is_approved', true);
        }

        $reviews      = $query->paginate(20)->withQueryString();
        $approvedCount = Review::where('is_approved', true)->count();
        $pendingCount  = Review::where('is_approved', false)->count();
        $avgRating     = number_format(Review::averageRating(), 1, ',', '');

        return view('admin.reviews.index', compact(
            'reviews', 'approvedCount', 'pendingCount', 'avgRating'
        ));
    }

    /* ============================================================
       APPROVE — toggle publié / masqué
    ============================================================ */
    public function approve(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);

        $state = $review->is_approved ? 'approuvé et publié' : 'masqué';
        return back()->with('success', 'Avis de ' . $review->first_name . ' ' . $state . '.');
    }

    /* ============================================================
       DESTROY — supprimer définitivement
    ============================================================ */
    public function destroy(Review $review)
    {
        $name = $review->first_name;
        $review->delete();

        return back()->with('success', 'Avis de ' . $name . ' supprimé définitivement.');
    }
}
