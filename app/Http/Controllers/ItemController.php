<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::select('id', 'name', 'price', 'image')->get();
        return response([
            'data' => $items
        ]);
    }

    public function show($id)
    {
        $item = Item::find($id);
        return response([
            'data' => $item
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'price' => 'required|integer',
            'image_file' => 'nullable|mimes:jpg,jpeg,png'
        ]);

        if ($request->file('image_file')) {
            $file = $request->file('image_file');
            $fileName = $file->getClientOriginalName();
            $newName = Carbon::now()->timestamp . '_' . $fileName;

            Storage::disk('public')->putFileAs('items', $file, $newName);
            $request['image'] = $newName;
        }

        $item = Item::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'price' => 'required|integer',
            'image_file' => 'nullable|mimes:jpg,jpeg,png'
        ]);

        if ($request->file('image_file')) {
            $file = $request->file('image_file');
            $fileName = $file->getClientOriginalName();
            $newName = Carbon::now()->timestamp . '_' . $fileName;

            Storage::disk('public')->putFileAs('items', $file, $newName);
            $request['image'] = $newName;
        }

        $item = Item::find($id);
        $item->update($request->all());

        return response([
            'data' => $item
        ]);
    }
}
