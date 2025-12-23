<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    public function index(){
        $allWatches = Watch::all();
        return view('admin.watches.index', compact('allWatches'));
    }

    public function add(){
        return view('admin.watches.create');
    }

    public function store(Request $req){
        $watchImage = null;

        if($req->hasFile('image')){
            $imagePath = $req->file('image')->store('photos','public');
        }
          
        $watch = new Watch;

        $watch->name= $req->name;
        $watch->price= $req->price;
        $watch->description = $req->description;
        $watch->image= $imagePath;
        $watch->featured= $req->featured;

        $watch->stock= $req->stock;

        $watch->save();

        return redirect()->route('adminDashboard')->with('success', 'Watch added successfully!');
    }

    public function update(Request $req){
        $id = $req->id;
        $watch= Watch::where('id', $id)->first();

        if($req->hasFile('image')){
            $imagePath = $req->file('image')->store('photos','public');
        } else {
            $imagePath = $watch->image;  
        }
          
        $watch->name= $req->name;
        $watch->price= $req->price;
        $watch->description = $req->description;
        $watch->image= $imagePath;
        $watch->featured= $req->featured;
        $watch->stock= $req->stock;

        $watch->save();

        return redirect()->route('adminDashboard')->with('success', 'Watch updated successfully!');
    }

    public function edit(Request $req){
        $id = $req->id;
        $watch = Watch::where('id', $id)->first();
        return view('admin.watches.edit', compact('watch'));
    }

    public function destroy(Request $req){
        $id= $req->id;
        Watch::destroy($id);
        return redirect()->route('adminDashboard')->with('success', 'Watch deleted successfully!');
    }
}
