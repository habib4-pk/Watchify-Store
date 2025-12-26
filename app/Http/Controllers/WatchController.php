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
 * WatchController
 * Handles product management: list, create, update, delete
 * Supports both AJAX (JSON) and traditional (redirect) responses
 */
class WatchController extends Controller
{
    private function getCloudinary()
    {
        // Initialize Cloudinary with direct config
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

    public function index()
    {
        try {
            $allWatches = Watch::all();
            return view('admin.watches.index', compact('allWatches'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load watches.');
        }
    }

    public function add()
    {
        try {
            return view('admin.watches.create');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to open create page.');
        }
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'additional_images' => 'nullable|array|max:10',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'featured' => 'required|in:yes,no',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $imageUrl = null;

            if ($req->hasFile('image')) {
                $file = $req->file('image');
                Log::info('Uploading primary image to Cloudinary', [
                    'filename' => $file->getClientOriginalName(),
                ]);
                
                // Upload to Cloudinary using direct SDK
                $cloudinary = $this->getCloudinary();
                $result = $cloudinary->upload($file->getRealPath(), [
                    'folder' => 'watches',
                ]);
                
                $imageUrl = $result['secure_url'];
                Log::info('Primary image upload success', ['url' => $imageUrl]);
            }

            $watch = new Watch;
            $watch->name = $req->name;
            $watch->price = $req->price;
            $watch->discount_percentage = $req->discount_percentage ?? 0;
            $watch->description = $req->description;
            $watch->image = $imageUrl;
            $watch->featured = $req->featured;
            $watch->stock = $req->stock;
            $watch->save();
            
            Log::info('Watch created', ['id' => $watch->id, 'name' => $watch->name]);

            // Save primary image to watch_images table
            if ($imageUrl) {
                WatchImage::create([
                    'watch_id' => $watch->id,
                    'image_url' => $imageUrl,
                    'sort_order' => 0,
                    'is_primary' => true,
                ]);
            }

            // Handle additional images
            if ($req->hasFile('additional_images')) {
                $cloudinary = $this->getCloudinary();
                $sortOrder = 1;
                foreach ($req->file('additional_images') as $additionalFile) {
                    $result = $cloudinary->upload($additionalFile->getRealPath(), [
                        'folder' => 'watches',
                    ]);
                    WatchImage::create([
                        'watch_id' => $watch->id,
                        'image_url' => $result['secure_url'],
                        'sort_order' => $sortOrder++,
                        'is_primary' => false,
                    ]);
                }
            }

            if ($req->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Watch added successfully!',
                    'watch' => [
                        'id' => $watch->id,
                        'name' => $watch->name,
                        'price' => $watch->price,
                        'image' => $watch->image,
                        'featured' => $watch->featured,
                        'stock' => $watch->stock
                    ],
                    'redirect' => route('adminDashboard')
                ]);
            }
            return redirect()->route('adminDashboard')->with('success', 'Watch added successfully!');
        } catch (Exception $e) {
            Log::error('Watch upload failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add watch: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to add watch: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id' => 'required|exists:watches,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'additional_images' => 'nullable|array|max:10',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'featured' => 'required|in:yes,no',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $id = $req->id;
            $watch = Watch::where('id', $id)->first();

            if (!$watch) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Watch not found.'], 404);
                }
                return redirect()->route('adminDashboard')->with('error', 'Watch not found.');
            }

            if ($req->hasFile('image')) {
                $file = $req->file('image');
                Log::info('Uploading updated image to Cloudinary', ['filename' => $file->getClientOriginalName()]);
                
                // Upload new image to Cloudinary
                $cloudinary = $this->getCloudinary();
                $result = $cloudinary->upload($file->getRealPath(), [
                    'folder' => 'watches',
                ]);
                
                $imageUrl = $result['secure_url'];
                Log::info('Cloudinary upload success', ['url' => $imageUrl]);
            } else {
                $imageUrl = $watch->image;
            }

            $watch->name = $req->name;
            $watch->price = $req->price;
            $watch->discount_percentage = $req->discount_percentage ?? 0;
            $watch->description = $req->description;
            $watch->image = $imageUrl;
            $watch->featured = $req->featured;
            $watch->stock = $req->stock;
            $watch->save();

            // Handle new primary image in watch_images table
            if ($req->hasFile('image')) {
                // Update existing primary or create new
                $existingPrimary = WatchImage::where('watch_id', $watch->id)
                    ->where('is_primary', true)
                    ->first();
                if ($existingPrimary) {
                    $existingPrimary->update(['image_url' => $imageUrl]);
                } else {
                    WatchImage::create([
                        'watch_id' => $watch->id,
                        'image_url' => $imageUrl,
                        'sort_order' => 0,
                        'is_primary' => true,
                    ]);
                }
            }

            // Handle additional images
            if ($req->hasFile('additional_images')) {
                $cloudinary = $this->getCloudinary();
                $maxOrder = WatchImage::where('watch_id', $watch->id)->max('sort_order') ?? 0;
                foreach ($req->file('additional_images') as $additionalFile) {
                    $result = $cloudinary->upload($additionalFile->getRealPath(), [
                        'folder' => 'watches',
                    ]);
                    WatchImage::create([
                        'watch_id' => $watch->id,
                        'image_url' => $result['secure_url'],
                        'sort_order' => ++$maxOrder,
                        'is_primary' => false,
                    ]);
                }
            }

            if ($req->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Watch updated successfully!',
                    'watch' => [
                        'id' => $watch->id,
                        'name' => $watch->name,
                        'price' => $watch->price,
                        'image' => $watch->image,
                        'featured' => $watch->featured,
                        'stock' => $watch->stock
                    ],
                    'redirect' => route('adminDashboard')
                ]);
            }
            return redirect()->route('adminDashboard')->with('success', 'Watch updated successfully!');
        } catch (Exception $e) {
            Log::error('Watch update failed', ['error' => $e->getMessage()]);
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update watch: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to update watch: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Request $req)
    {
        try {
            $id = $req->id;
            $watch = Watch::with('images')->where('id', $id)->first();

            if (!$watch) {
                return redirect()->route('adminDashboard')->with('error', 'Watch not found.');
            }

            return view('admin.watches.edit', compact('watch'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to open edit page.');
        }
    }

    public function destroy(Request $req)
    {
        try {
            $id = $req->id;
            $watch = Watch::find($id);
            
            if (!$watch) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Watch not found.'], 404);
                }
                return redirect()->route('adminDashboard')->with('error', 'Watch not found.');
            }
            
            $watchName = $watch->name;
            Watch::destroy($id);
            
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Watch '{$watchName}' deleted successfully!"
                ]);
            }
            return redirect()->route('adminDashboard')->with('success', 'Watch deleted successfully!');
        } catch (Exception $e) {
            if ($req->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete watch.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to delete watch.');
        }
    }
}