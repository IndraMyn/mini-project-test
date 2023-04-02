<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {

            $data = Product::select(["name", "price"])->get();

            return response([
                "data" => $data
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
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'price' => 'required',
                'description' => 'required',
                'stock' => 'required'
            ]);
    
            if ($validator->fails()) {
                return response($validator->errors(), 422);
            }

            $create = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'stock' => $request->stock
            ]);

            return response([
                'data' => $create,
                'message' => 'Successfully'
            ]);

        } catch (Exception $e) {

            return response(['error' => $e->getMessage()], 500);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $product = Product::find($id);

            return response([
                'data' => $product
            ]);

        } catch (Exception $e) {

            return response(['error' => $e->getMessage()], 500);

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}