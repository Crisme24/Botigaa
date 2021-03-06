<?php

namespace App\Http\Controllers;

use App\Mail\OrderShipped;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function getAll()
    {
        try {
            //code...
            $orders = Order::with(['products.categories', 'user'])->get();
            return response($orders);
        } catch (\Exception $e) {
            //throw $th;
            return response($e, 500);
        }

    }

    public function insert(Request $request)
    {
        try {
            $body = $request->validate([
                'deliveryDate' => 'required|date',
                'products' => 'required|array'
            ]);
            $body['status'] = 'pending';
            $body['user_id'] = Auth::id();
            $products = $body['products'];
            unset($body['products']);
            $order = Order::create($body);
            $order->products()->attach($products);
            $order = $order->load('user', 'products');//Load es para cargar la instancia de una relacion
            //dd($order->user->email, $order);
            Mail::to($order->user->email)->send(new OrderShipped($order));
            return response($order, 201);
        } catch (\Exception $e) {
            //throw $th;
            return response($e, 500);
        }
    }
}
