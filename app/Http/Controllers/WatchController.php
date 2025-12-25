<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use Illuminate\Http\Request;
use Exception;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class WatchController extends Controller
{
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
        // 1. Validation Logic
        $req->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
            'featured' => 'required|in:yes,no',
            'stock' => 'required|integer|min:0',
        ]);

        try {
            $imageUrl = null;

            if ($req->hasFile('image')) {
                // Upload to Cloudinary
                $uploadedFile = Cloudinary::upload($req->file('image')->getRealPath(), [
                    'folder' => 'watches',
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto'
                    ]
                ]);
                $imageUrl = $uploadedFile->getSecurePath();
            }

            $watch = new Watch;
            $watch->name = $req->name;
            $watch->price = $req->price;
            $watch->description = $req->description;
            $watch->image = $imageUrl;
            $watch->featured = $req->featured;
            $watch->stock = $req->stock;
            $watch->save();

            return redirect()->route('adminDashboard')->with('success', 'Watch added successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to add watch: ' . $e->getMessage());
        }
    }

    public function update(Request $req)
    {
        // 1. Validation Logic
        $req->validate([
            'id' => 'required|exists:watches,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'featured' => 'required|in:yes,no',
            'stock' => 'required|integer|min:0',
        ]);

        try {
            $id = $req->id;
            $watch = Watch::where('id', $id)->first();

            if (!$watch) {
                return redirect()->route('adminDashboard')->with('error', 'Watch not found.');
            }

            if ($req->hasFile('image')) {
                // Upload new image to Cloudinary
                $uploadedFile = Cloudinary::upload($req->file('image')->getRealPath(), [
                    'folder' => 'watches',
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto'
                    ]
                ]);
                $imageUrl = $uploadedFile->getSecurePath();
            } else {
                $imageUrl = $watch->image;
            }

            $watch->name = $req->name;
            $watch->price = $req->price;
            $watch->description = $req->description;
            $watch->image = $imageUrl;
            $watch->featured = $req->featured;
            $watch->stock = $req->stock;
            $watch->save();

            return redirect()->route('adminDashboard')->with('success', 'Watch updated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update watch: ' . $e->getMessage());
        }
    }

    public function edit(Request $req)
    {
        try {
            $id = $req->id;
            $watch = Watch::where('id', $id)->first();

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
            Watch::destroy($id);
            return redirect()->route('adminDashboard')->with('success', 'Watch deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete watch.');
        }
    }
}