<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $foods = Food::all();
        return view('customer.index', compact('foods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'table_number'  => 'required|numeric|min:1|max:99',
            'items'         => 'required|array',
            'items.*'       => 'nullable|integer|min:0',
        ], [
            'table_number.required' => 'Nomor meja wajib diisi.',
            'table_number.numeric'  => 'Nomor meja harus berupa angka.',
            'table_number.min'      => 'Nomor meja minimal 1.',
            'customer_name.required'=> 'Nama wajib diisi.',
            'items.required'        => 'Pilih minimal satu menu.',
        ]);

        $orderedItems = array_filter($validated['items'], fn ($qty) => (int)$qty > 0);

        if (empty($orderedItems)) {
            return back()->withInput()->with('error', 'Pilih minimal satu menu makanan!');
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'table_number'  => (int) $validated['table_number'],
                'total_price'   => 0,
                'status'        => 'pending',
            ]);

            $totalPrice = 0;

            foreach ($orderedItems as $foodId => $quantity) {
                $food = Food::findOrFail($foodId);
                $quantity = (int) $quantity;
                $subtotal = $food->price * $quantity;
                $totalPrice += $subtotal;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'food_id'  => $food->id,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_price' => $totalPrice]);
            DB::commit();

            return redirect()->route('customer.index')->with('success', 'Pesanan berhasil dibuat! Nomor Meja: ' . $order->table_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

  public function adminDashboard()
{
    $orders = Order::with('orderDetails.food')
        ->latest()
        ->get();

    return view('dashboard', compact('orders'));
}

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,diproses,selesai',
    ]);

    $order = Order::findOrFail($id);

    $order->status = $request->input('status');
    $order->save();

    return redirect()
        ->route('admin.dashboard')
        ->with('success', 'Status pesanan #' . $order->id . ' berhasil diperbarui!');
}

}