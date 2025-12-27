<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Watch;
use App\Models\User;
use App\Models\Review;
use App\Models\HeroBanner;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * ShopController
 * Handles public-facing shop pages: home, featured, search, product details, about us
 */
class ShopController extends Controller
{
    /**
     * Display the homepage with all watches
     */
    public function home(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'sort' => 'nullable|string|in:price_asc,price_desc,name_az,newest'
            ]);

            if ($validator->fails()) {
                $sort = null;
            } else {
                $sort = $req->input('sort');
            }

            $query = Watch::query();

            if ($sort == 'price_asc') $query->orderBy('price', 'asc');
            elseif ($sort == 'price_desc') $query->orderBy('price', 'desc');
            elseif ($sort == 'name_az') $query->orderBy('name', 'asc');
            else $query->orderBy('created_at', 'desc');

            $allWatches = $query->get();
            $heroBanners = HeroBanner::active()->get();
            return view('buyer.home', compact('allWatches', 'sort', 'heroBanners'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to load watches.');
        }
    }

    /**
     * Display featured watches
     */
    public function featured(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'sort' => 'nullable|string|in:price_asc,price_desc,name_az,newest'
            ]);

            if ($validator->fails()) {
                $sort = null;
            } else {
                $sort = $req->input('sort');
            }

            $query = Watch::where('featured', 'yes');

            if ($sort == 'price_asc') $query->orderBy('price', 'asc');
            elseif ($sort == 'price_desc') $query->orderBy('price', 'desc');
            elseif ($sort == 'name_az') $query->orderBy('name', 'asc');
            else $query->orderBy('created_at', 'desc');

            $allWatches = $query->get();
            return view('buyer.featured', compact('allWatches', 'sort'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to load featured watches.');
        }
    }

    /**
     * Search for watches
     */
    public function search(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'query' => 'nullable|string|max:100',
                'sort' => 'nullable|string|in:price_asc,price_desc,name_az,newest'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid search parameters.');
            }

            $queryStr = strip_tags(trim($req->input('query', '')));
            
            // Handle empty or whitespace-only queries
            if (empty($queryStr)) {
                return redirect()->route('shop.index')->with('info', 'Please enter a search term.');
            }
            
            $sort = $req->input('sort');
            
            // Search in name and description for better results
            $query = Watch::where(function($q) use ($queryStr) {
                $q->where('name', 'LIKE', "%{$queryStr}%")
                  ->orWhere('description', 'LIKE', "%{$queryStr}%");
            });

            if ($sort == 'price_asc') $query->orderBy('price', 'asc');
            elseif ($sort == 'price_desc') $query->orderBy('price', 'desc');
            elseif ($sort == 'name_az') $query->orderBy('name', 'asc');
            else $query->orderBy('created_at', 'desc');

            $allWatches = $query->get();
            return view('buyer.search-results', compact('allWatches', 'queryStr', 'sort'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Search failed. Please try again.');
        }
    }

    /**
     * Display single watch details
     */
    public function details(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'id' => 'required|integer|exists:watches,id'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid watch ID.');
            }

            $id = $req->id;
            $watch = Watch::with('images')->where('id', $id)->first();

            if (!$watch) {
                return redirect()->back()->with('error', 'Watch not found.');
            }

            $reviews = Review::where('watch_id', $id)->orderBy('created_at', 'desc')->get();

            foreach ($reviews as $review) {
                $review->user = User::where('id', $review->user_id)->first();
            }

            $totalRating = 0;
            $count = count($reviews);

            foreach ($reviews as $review) {
                $totalRating += $review->rating;
            }

            $avgRating = ($count > 0) ? ($totalRating / $count) : 0;

            return view('buyer.product-detail', compact('watch', 'reviews', 'avgRating'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while fetching details.');
        }
    }

    /**
     * Display about us page
     */
    public function aboutUs()
    {
        return view('buyer.about');
    }
}
