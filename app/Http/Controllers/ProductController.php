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


    /**
     * @OA\Get(
     *     path="/products",
     *     tags={"Products"},
     *     operationId="allProducts",
     *     summary="List all products",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Products found",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "success",
     *                 "message": "Product found",
     *                 "data": "Array of products",
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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

    /**
     * @OA\Post(
     *     path="/products",
     *     tags={"Products"},
     *     operationId="storeProduct",
     *     summary="Create a new product",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name", "price", "image", "category_product_id"},
     *                 @OA\Property(property="name", type="string", example="Sample Product"),
     *                 @OA\Property(property="price", type="integer", example=100),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="category_product_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "success",
     *                 "message": "Product created successfully",
     *                 "data": {
     *                     "id": 1,
     *                     "name": "Sample Product",
     *                     "price": 100,
     *                     "category_product_id": 1,
     *                     "image": "path/to/image.jpg"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "failed",
     *                 "message": "create Product failed!",
     *                 "errors": {
     *                     "name": "array of error",
     *                     "price": "array of error",
     *                     "image": "array of error",
     *                     "category_product_id": "array of error"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category Product not found",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "failed",
     *                 "message": "Category Product not found!"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     operationId="showProduct",
     *     summary="Get a product by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product found",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "success",
     *                 "message": "Product found",
     *                 "data": {
     *                     "id": 1,
     *                     "name": "Sample Product",
     *                     "price": 100,
     *                     "category_product_id": 1,
     *                     "image": "path/to/image.jpg"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "failed",
     *                 "message": "Product not found"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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

    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     operationId="updateProduct",
     *     summary="Update an existing product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="Updated Product"),
     *                 @OA\Property(property="price", type="integer", example=150),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="category_product_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "success",
     *                 "message": "Product updated successfully",
     *                 "data": {
     *                     "id": 1,
     *                     "name": "Updated Product",
     *                     "price": 150,
     *                     "category_product_id": 1,
     *                     "image": "path/to/new-image.jpg"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "failed",
     *                 "message": "Product not found"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "failed",
     *                 "message": "update Product failed!",
     *                 "errors": {
     *                     "name": "array of error",
     *                     "price": "array of error",
     *                     "image": "array of error",
     *                     "category_product_id": "array of error"
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     tags={"Products"},
     *     operationId="deleteProduct",
     *     summary="Delete a product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "success",
     *                 "message": "Product deleted successfully!"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "failed",
     *                 "message": "Product not found"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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
