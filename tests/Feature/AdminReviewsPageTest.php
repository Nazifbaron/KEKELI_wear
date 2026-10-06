<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminReviewsPageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_reviews_page_receives_stats_and_supports_filters_and_deletion(): void
    {
        $admin = User::create([
            'name' => 'Admin test',
            'email' => 'admin-reviews-' . uniqid() . '@example.com',
            'password' => 'password',
            'is_admin' => true,
        ]);
        $approved = Review::create([
            'first_name' => 'Client approuvée',
            'content' => 'Un avis client suffisamment long pour le test.',
            'rating' => 5,
            'is_approved' => true,
        ]);
        $pending = Review::create([
            'first_name' => 'Client en attente',
            'content' => 'Un autre avis client suffisamment long pour le test.',
            'rating' => 4,
            'is_approved' => false,
        ]);
        $expectedAverage = number_format((float) Review::approved()->avg('rating'), 1);

        $this->actingAs($admin)
            ->get(route('admin.reviews'))
            ->assertOk()
            ->assertSee('Avis reçus')
            ->assertSee('Publiés')
            ->assertSee('En attente')
            ->assertSee($expectedAverage);

        $this->actingAs($admin)
            ->get(route('admin.reviews', ['filter' => 'pending']))
            ->assertOk()
            ->assertSee('Client en attente')
            ->assertDontSee('Client approuvée');

        $this->actingAs($admin)
            ->delete(route('admin.reviews.destroy', $pending))
            ->assertRedirect();

        $this->assertDatabaseMissing('reviews', ['id' => $pending->id]);
        $this->assertDatabaseHas('reviews', ['id' => $approved->id]);
    }
}