<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoryProduct = CategoryProduct::latest()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Category Product found',
            'data' => $categoryProduct,
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
            'name' => 'required|unique:category_products,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "failed",
                "message" => "create Category Product failed!",
                "errors" => $validator->errors()
            ], 422);
        }

        $categoryProduct = CategoryProduct::create($request->all());

        return response()->json([
            "status" => "success",
            "message" => "Category Product created successfully",
            "data" => $categoryProduct
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoryProduct = CategoryProduct::find($id);

        if (!$categoryProduct) {
            return response()->json([
                "status" => "failed",
                "message" => "Category Product not found"
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "message" => "Category Product found",
            "data" => $categoryProduct
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $categoryProduct = CategoryProduct::find($id);
        if (!$categoryProduct) {
            return response()->json([
                "status" => "failed",
                "message" => "Category Product not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:category_products,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "failed",
                "message" => "update Category Product failed!",
                "errors" => $validator->errors()
            ], 422);
        }

        $categoryProduct->update([
            'name' => $request->name,
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Category Product updated successfully",
            "data" => $categoryProduct
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categoryProduct = CategoryProduct::find($id);

        if (!$categoryProduct) {
            return response()->json([
                "status" => "failed",
                "message" => "Category Product not found"
            ], 404);
        }

        $categoryProduct->delete();

        return response()->json([
            "status" => "success",
            "message" => "Category Product deleted successfully!"
        ], 200);
    }
}
