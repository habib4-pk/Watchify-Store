<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $req)
    {
        if (!Auth::user()) {
            return redirect()->route('login')->with('error', 'Please login to add a review.');
        }

        $req->validate([
            'watch_id' => 'required|exists:watches,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $user_id = Auth::id();
        $watch_id = $req->watch_id;
        $rating = $req->rating;
        $comment = $req->comment;

        $existingReview = Review::where('user_id', $user_id)->where('watch_id', $watch_id)->first();

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
    }

   public function delete(Request $req)
{
    $id = $req->input('review_id');  // fix here
    
    $review = Review::where('id', $id)->first();

    if (!$review) {
        return redirect()->back()->with('error', 'Review not found.');
    }

    if (!Auth::check() || Auth::id() != $review->user_id) {
        return redirect()->back()->with('error', 'Unauthorized access.');
    }

    Review::destroy($id);

    return redirect()->back()->with('success', 'Review deleted successfully.');
}

}
