<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Product = Product::latest()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Product found',
            'data' => $Product,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|integer|min:0',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            "category_product_id" => "required|integer"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "failed",
                "message" => "create Product failed!",
                "errors" => $validator->errors()
            ], 422);
        }

        if ($request->category_product_id) {
            $categoryProduct = CategoryProduct::find($request->category_product_id);

            if (!$categoryProduct) {
                return response()->json([
                    "status" => "failed",
                    "message" => "Category Product not found!",
                ], 404);
            }
        }

        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_product_id = $request->category_product_id;


        if ($request->hasFile("image")) {
            $file = $request->file("image");
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($request->name) . "-" . time() . "." . $extension;
            $path = $file->storeAs("products", $fileName);
            $product->image = $path;
        }

        $product->save();

        return response()->json([
            "status" => "success",
            "message" => "Product created successfully",
            "data" => Product::with("category")->find($product->id)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with("category")->find($id);

        if (!$product) {
            return response()->json([
                "status" => "failed",
                "message" => "Product not found"
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "message" => "Product found",
            "data" => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                "status" => "failed",
                "message" => "Product not found"
            ], 404);
        }


        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'price' => 'integer|min:0',
            'image' => 'image|mimes:png,jpg,jpeg|max:2048',
            "category_product_id" => "integer"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "failed",
                "message" => "update Product failed!",
                "errors" => $validator->errors()
            ], 422);
        }

        if ($request->name) {
            $product->name = $request->name;
        }
        if ($request->price) {
            $product->price = $request->price;
        }
        if ($request->category_product_id) {
            $product->category_product_id = $request->category_product_id;
        }

        if ($request->hasFile("image")) {
            if ($product->image) {
                Storage::delete($product->image);
            }

            $file = $request->file("image");
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($request->name) . "-" . time() . "." . $extension;
            $path = $file->storeAs("products", $fileName);
            $product->image = $path;
        }

        $product->save();

        return response()->json([
            "status" => "success",
            "message" => "Product updated successfully",
            "data" => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                "status" => "failed",
                "message" => "Product not found"
            ], 404);
        }

        $product->delete();

        if ($product->image) {
            Storage::delete($product->image);
        }

        return response()->json([
            "status" => "success",
            "message" => "Product deleted successfully!"
        ], 200);
    }
}
