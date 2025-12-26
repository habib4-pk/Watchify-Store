<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use App\Models\WatchImage;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

/**
 * WatchImageController
 * Handles image management for watches: upload, delete, reorder, set primary
 */
class WatchImageController extends Controller
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
     * Store additional images for a watch.
     */
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'watch_id' => 'required|exists:watches,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $watch = Watch::findOrFail($req->watch_id);
            $cloudinary = $this->getCloudinary();
            $uploadedImages = [];
            
            // Get the current max sort order
            $maxOrder = WatchImage::where('watch_id', $watch->id)->max('sort_order') ?? -1;
            
            foreach ($req->file('images') as $index => $file) {
                Log::info('Uploading additional image to Cloudinary', [
                    'filename' => $file->getClientOriginalName(),
                    'watch_id' => $watch->id
                ]);
                
                $result = $cloudinary->upload($file->getRealPath(), [
                    'folder' => 'watches',
                ]);
                
                $imageUrl = $result['secure_url'];
                Log::info('Cloudinary upload success', ['url' => $imageUrl]);
                
                $watchImage = WatchImage::create([
                    'watch_id' => $watch->id,
                    'image_url' => $imageUrl,
                    'sort_order' => $maxOrder + $index + 1,
                    'is_primary' => false,
                ]);
                
                $uploadedImages[] = [
                    'id' => $watchImage->id,
                    'image_url' => $watchImage->image_url,
                    'sort_order' => $watchImage->sort_order,
                    'is_primary' => $watchImage->is_primary,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedImages) . ' image(s) uploaded successfully!',
                'images' => $uploadedImages
            ]);
        } catch (Exception $e) {
            Log::error('Image upload failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an image from a watch.
     */
    public function destroy(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'image_id' => 'required|exists:watch_images,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $image = WatchImage::findOrFail($req->image_id);
            $wasPrimary = $image->is_primary;
            $watchId = $image->watch_id;
            
            $image->delete();
            
            // If deleted image was primary, set first remaining image as primary
            if ($wasPrimary) {
                $firstImage = WatchImage::where('watch_id', $watchId)
                    ->orderBy('sort_order')
                    ->first();
                if ($firstImage) {
                    $firstImage->update(['is_primary' => true]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully!'
            ]);
        } catch (Exception $e) {
            Log::error('Image delete failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image.'
            ], 500);
        }
    }

    /**
     * Update the sort order of images.
     */
    public function updateOrder(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'watch_id' => 'required|exists:watches,id',
            'order' => 'required|array',
            'order.*' => 'integer|exists:watch_images,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($req->order as $index => $imageId) {
                WatchImage::where('id', $imageId)
                    ->where('watch_id', $req->watch_id)
                    ->update(['sort_order' => $index]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Image order updated successfully!'
            ]);
        } catch (Exception $e) {
            Log::error('Image reorder failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update image order.'
            ], 500);
        }
    }

    /**
     * Set an image as the primary image.
     */
    public function setPrimary(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'image_id' => 'required|exists:watch_images,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $image = WatchImage::findOrFail($req->image_id);
            
            // Remove primary from all other images of this watch
            WatchImage::where('watch_id', $image->watch_id)
                ->update(['is_primary' => false]);
            
            // Set this image as primary
            $image->update(['is_primary' => true]);
            
            // Also update the main image column on the watch
            $watch = Watch::findOrFail($image->watch_id);
            $watch->update(['image' => $image->image_url]);

            return response()->json([
                'success' => true,
                'message' => 'Primary image updated successfully!',
                'image_url' => $image->image_url
            ]);
        } catch (Exception $e) {
            Log::error('Set primary failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to set primary image.'
            ], 500);
        }
    }
}
