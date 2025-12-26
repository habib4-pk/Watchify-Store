<?php

namespace App\Http\Controllers;

use App\Models\HeroBanner;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

/**
 * HeroBannerController
 * Handles hero banner management: list, create, update, delete, reorder
 */
class HeroBannerController extends Controller
{
    private function getCloudinary()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud.cloud_name') ?: env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => config('cloudinary.cloud.api_key') ?: env('CLOUDINARY_API_KEY'),
                'api_secret' => config('cloudinary.cloud.api_secret') ?: env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);
        
        return new UploadApi();
    }

    /**
     * Display all banners for admin management.
     */
    public function index()
    {
        $banners = HeroBanner::orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Store a new banner.
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'title' => 'nullable|string|max:100',
            'subtitle' => 'nullable|string|max:200',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cloudinary = $this->getCloudinary();
            $file = $req->file('image');
            
            $result = $cloudinary->upload($file->getRealPath(), [
                'folder' => 'hero-banners',
            ]);
            
            $maxOrder = HeroBanner::max('sort_order') ?? -1;
            
            $banner = HeroBanner::create([
                'image_url' => $result['secure_url'],
                'title' => $req->title,
                'subtitle' => $req->subtitle,
                'button_text' => $req->button_text,
                'button_link' => $req->button_link,
                'sort_order' => $maxOrder + 1,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Banner added successfully!',
                'banner' => $banner
            ]);
        } catch (Exception $e) {
            Log::error('Banner upload failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to add banner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update banner details.
     */
    public function update(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id' => 'required|exists:hero_banners,id',
            'title' => 'nullable|string|max:100',
            'subtitle' => 'nullable|string|max:200',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $banner = HeroBanner::findOrFail($req->id);
            $banner->update([
                'title' => $req->title,
                'subtitle' => $req->subtitle,
                'button_text' => $req->button_text,
                'button_link' => $req->button_link,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Banner updated successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update banner.'
            ], 500);
        }
    }

    /**
     * Delete a banner.
     */
    public function destroy(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id' => 'required|exists:hero_banners,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            HeroBanner::destroy($req->id);
            return response()->json([
                'success' => true,
                'message' => 'Banner deleted successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete banner.'
            ], 500);
        }
    }

    /**
     * Toggle banner active status.
     */
    public function toggleActive(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id' => 'required|exists:hero_banners,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $banner = HeroBanner::findOrFail($req->id);
            $banner->update(['is_active' => !$banner->is_active]);

            return response()->json([
                'success' => true,
                'message' => $banner->is_active ? 'Banner activated!' : 'Banner deactivated!',
                'is_active' => $banner->is_active
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle banner.'
            ], 500);
        }
    }

    /**
     * Update banner order.
     */
    public function updateOrder(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'order' => 'required|array',
            'order.*' => 'integer|exists:hero_banners,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($req->order as $index => $id) {
                HeroBanner::where('id', $id)->update(['sort_order' => $index]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order.'
            ], 500);
        }
    }
}
