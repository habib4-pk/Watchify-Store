<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use Exception;

class ReviewController extends Controller
{
    public function store(Request $req)
    {
        // 1. Validate the incoming request data
        $req->validate([
            'watch_id' => 'required|exists:watches,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:500',
        ]);

        try {
            if (!Auth::check()) {
                return redirect()->route('account.login')->with('error', 'Please login to add a review.');
            }

            $user_id = Auth::id();
            $watch_id = $req->watch_id;
            $rating = $req->rating;
            $comment = $req->comment;

            // Check if this user has already reviewed this specific watch
            $existingReview = Review::where('user_id', $user_id)
                                    ->where('watch_id', $watch_id)
                                    ->first();

            if ($existingReview) {
                $existingReview->rating = $rating;
                $existingReview->comment = $comment;
                $existingReview->save();

                return redirect()->back()->with('success', 'Review updated successfully.');
            } else {
                $review = new Review;
                $review->user_id = $user_id;
                $review->watch_id = $watch_id;
                $review->rating = $rating;
                $review->comment = $comment;
                $review->save();

                return redirect()->back()->with('success', 'Review added successfully.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while saving your review.');
        }
    }

    public function delete(Request $req)
    {
        // 1. Validate that the review_id is provided and exists in the reviews table
        $req->validate([
            'review_id' => 'required|exists:reviews,id',
        ]);

        try {
            $id = $req->review_id;
            $review = Review::find($id);

            // 2. Ensure the logged-in user is the owner of the review
            if (Auth::id() != $review->user_id) {
                return redirect()->back()->with('error', 'Unauthorized access.');
            }

            $review->delete();

            return redirect()->back()->with('success', 'Review deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the review.');
        }
    }
}