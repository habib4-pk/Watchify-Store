<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * ReviewController
 * Handles review operations: store, delete
 * Supports both AJAX (JSON) and traditional (redirect) responses
 */
class ReviewController extends Controller
{
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'watch_id' => 'required|exists:watches,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        try {
            if (!Auth::check()) {
                if ($req->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please login to add a review.'
                    ], 401);
                }
                return redirect()->route('account.login')->with('error', 'Please login to add a review.');
            }

            $user_id = Auth::id();
            $user = Auth::user();
            $watch_id = $req->watch_id;
            $rating = $req->rating;
            $comment = $req->comment;

            // Check if this user has already reviewed this specific watch
            $existingReview = Review::where('user_id', $user_id)
                                    ->where('watch_id', $watch_id)
                                    ->first();

            $isUpdate = false;
            if ($existingReview) {
                $existingReview->rating = $rating;
                $existingReview->comment = $comment;
                $existingReview->save();
                $review = $existingReview;
                $isUpdate = true;
                $message = 'Review updated successfully.';
            } else {
                $review = new Review;
                $review->user_id = $user_id;
                $review->watch_id = $watch_id;
                $review->rating = $rating;
                $review->comment = $comment;
                $review->save();
                $message = 'Review added successfully.';
            }

            if ($req->expectsJson()) {
                // Calculate new average rating
                $reviews = Review::where('watch_id', $watch_id)->get();
                $avgRating = $reviews->count() > 0 ? $reviews->avg('rating') : 0;

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'isUpdate' => $isUpdate,
                    'review' => [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'created_at' => $review->created_at->diffForHumans(),
                        'user' => [
                            'name' => $user->name,
                            'initial' => strtoupper(substr($user->name, 0, 1))
                        ]
                    ],
                    'avgRating' => round($avgRating, 1),
                    'reviewCount' => $reviews->count()
                ]);
            }
            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while saving your review.'
                ], 500);
            }
            return redirect()->back()->with('error', 'An error occurred while saving your review.');
        }
    }

    public function delete(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'review_id' => 'required|exists:reviews,id',
        ]);

        if ($validator->fails()) {
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid review.'
                ], 400);
            }
            return redirect()->back()->withErrors($validator);
        }

        try {
            $id = $req->review_id;
            $review = Review::find($id);

            // Ensure the logged-in user is the owner of the review
            if (Auth::id() != $review->user_id) {
                if ($req->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Unauthorized access.');
            }

            $watch_id = $review->watch_id;
            $review->delete();

            if ($req->expectsJson()) {
                // Calculate new average rating
                $reviews = Review::where('watch_id', $watch_id)->get();
                $avgRating = $reviews->count() > 0 ? $reviews->avg('rating') : 0;

                return response()->json([
                    'success' => true,
                    'message' => 'Review deleted successfully.',
                    'avgRating' => round($avgRating, 1),
                    'reviewCount' => $reviews->count()
                ]);
            }
            return redirect()->back()->with('success', 'Review deleted successfully.');
        } catch (Exception $e) {
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while deleting the review.'
                ], 500);
            }
            return redirect()->back()->with('error', 'An error occurred while deleting the review.');
        }
    }
}