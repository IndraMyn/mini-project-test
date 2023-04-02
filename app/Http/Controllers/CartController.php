<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Validator;

class CartController extends Controller
{

    protected $userId;

    public function __construct()
    {
        try {
            $this->userId = JWTAuth::parseToken()->authenticate()->_id;
        } catch (\Throwable) {

        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $user = User::find($this->userId);
            $carts = $user->carts()->get();
            $response = array();

            foreach ($carts as $cart) {
                
                $product = Product::find($cart->product_id);

                array_push($response, [
                    '_id' => $cart->_id,
                    'qty'=> $cart->qty,
                    'product_id' => $cart->product_id,
                    'name' => $product->name,
                    'price' => $product->price
                ]);

            }

            return response([
                'data' => $response,
            ]);

        } catch (Exception $e) {

            return response(['error' => $e->getMessage()], 500);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $cart = new Cart([
            'product_id' => $request->product_id,
            'qty' => $request->qty
        ]);

        $user = User::find($this->userId);
        $user->carts()->save($cart);

        return response([
            'message' => "Successfully"
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($this->userId);
        $cart = $user->carts()->find($id);

        if (empty($cart))
            return response([
                "message" => "ID {$id} is not found!"
            ], 400);

        $cart->delete();
        return response([
            "message" => "Successfully"
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'products' => 'required',
            'payment_method' =>'required',
            'shipment_type' =>'required',
            'address' =>'required',
            "phone_number" =>'required'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 422);
        }

        $user = User::find($this->userId);
        $products = array();

        foreach ($request->carts as $cart_id) {

            $cart = $user->carts()->find($cart_id);
            $product = Product::find($cart->product_id);

            array_push($products, [
                'name' => $product->name,
                'price' => $product->price,
                'qty' => $cart->qty,
            ]);

            $cart->delete();

        }

        $order = new Order([
            'products' => $products,
            'payment_method' => $request->payment_method,
            'shipment_type' => $request->shipment_type,
            'address' => $request->address,
            "phone_number" => $request->phone_number
        ]);

        $user->orders()->save($order);

        return response([
            'message' => "Successfully",
            'data' => $products
        ]);

    }
}