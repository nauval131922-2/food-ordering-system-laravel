<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|max:100',
            'table_no' => 'required|max:5',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['customer_name', 'table_no']);
            $data['order_date'] = date('Y-m-d');
            $data['order_time'] = date('H:i:s');
            $data['status'] = 'ordered';
            $data['total'] = 0;
            $data['waitress_id'] = auth()->user()->id;
            $data['items'] = $request->items;

            $order = Order::create($data);

            collect($data['items'])->map(function ($item) use ($order) {
                $foodDrink = Item::where('id', $item)->first();
                OrderDetail::create([
                    'order_id' => $order->id,
                    'item_id' => $item,
                    'price' => $foodDrink->price,
                ]);
            });

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response($th);
        }

        return response([
            'data' => $order
        ]);
    }
}
