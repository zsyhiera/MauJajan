<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    /**
     * Tampilkan semua data makanan
     */
    public function index()
    {
        $foods = Food::latest()->paginate(10);
        return view('admin.foods.index', compact('foods'));
    }

    /**
     * Tampilkan form tambah makanan
     */
    public function create()
    {
        return view('admin.foods.create');
    }

    /**
     * Simpan data makanan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:Makanan,Minuman,Cemilan',
            'price'       => 'required|numeric|min:0',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('foods', 'public');
        }

        Food::create([
            'name'        => $request->name,
            'category'    => $request->category,
            'price'       => $request->price,
            'description' => $request->description,
            'image'       => $imagePath,
        ]);

        return redirect()->route('foods.index')
            ->with('success', 'Data makanan berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit makanan
     */
    public function edit(Food $food)
    {
        return view('admin.foods.edit', compact('food'));
    }

    /**
     * Update data makanan
     */
    public function updateStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);

    $order->status = $request->status;
    $order->save();

    return redirect()
        ->route('admin.dashboard')
        ->with('success', 'Status berhasil diubah menjadi: ' . $order->status);
}

    /**
     * Hapus data makanan
     */
    public function destroy(Food $food)
    {
        // Hapus gambar dari storage jika ada
        if ($food->image && Storage::disk('public')->exists($food->image)) {
            Storage::disk('public')->delete($food->image);
        }

        $food->delete();

        return redirect()->route('foods.index')
            ->with('success', 'Data makanan berhasil dihapus!');
    }
}