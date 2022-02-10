<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        Product::increaseViews($products, auth()->user(), \Carbon\Carbon::today());
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            'name' => 'required|string|max:100',
            'description' => 'required',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 400);
        }

        $product = Product::create($validator->valid());

        return response()->json(new ProductResource($product), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        Product::increaseViews($product, auth()->user(), \Carbon\Carbon::today());
        return response()->json(new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        $product->fill($validated)->save();
        return response()->json(new ProductResource($product), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response(null,204);
    }
}
