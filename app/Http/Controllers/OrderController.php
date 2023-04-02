<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{

    protected $userId;

    public function __construct()
    {
        try {
            $this->userId = JWTAuth::parseToken()->authenticate()->_id;
        } catch (\Throwable) {

        }
    }

    public function index()
    {

        try {

            $user = User::find($this->userId);
            $orders = $user->orders()->get();
            $response = array();

            foreach ($orders as $item) {

                foreach ($item->products as $product) {

                    array_push($response, [
                        'order_id' => $item->id,
                        'product' => $product,
                        'created_at' => $item->created_at
                    ]);

                }

            }

            return response([
                'data' => $response
            ]);

        } catch (Exception $e) {

            return response(['error' => $e->getMessage()], 500);

        }

    }

    public function summary($id)
    {
        try {

            $user = User::find($this->userId);
            $order = $user->orders()->find($id);

            $response = [
                "shipping" => [
                    "name" => $user->name,
                    "phone_number" => $order->phone_number,
                    "address" => $order->address,
                    "created_at" => $order->created_at
                ],
                "products" => $order->products,
                "payment" => [
                    "payment_method" => $order->payment_method
                ]

            ];


            return response([
                'data' => $response
            ]);

        } catch (Exception $e) {

            return response(['error' => $e->getMessage()], 500);

        }
    }
}