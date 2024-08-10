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

    /**
     * @OA\Get(
     *     path="/category-products",
     *     tags={"Category Products"},
     *     operationId="allCategoryProduct",
     *     summary="List all category products",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Category products found",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "success",
     *                 "message": "Category Product found",
     *                 "data": "Array of category product",
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

    /**
     * @OA\Post(
     *     path="/category-products",
     *     tags={"Category Products"},
     *     operationId="storeCategoryProduct",
     *     summary="Create a new category product",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="New Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category Product created successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "success",
     *                 "message": "Category Product created successfully",
     *                 "data": {
     *                     "id": 1,
     *                     "name": "New Category"
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
     *                 "message": "create Category Product failed!",
     *                 "errors": {
     *                     "name": "array of error",
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

    /**
     * @OA\Get(
     *     path="/category-products/{id}",
     *     tags={"Category Products"},
     *     operationId="showCategoryProduct",
     *     summary="Get a category product by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category Product found",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "success",
     *                 "message": "Category Product found",
     *                 "data": {
     *                     "id": 1,
     *                     "name": "HP"
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
     *                 "message": "Category Product not found"
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


    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/category-products/{id}",
     *     tags={"Category Products"},
     *     operationId="updateCategoryProduct",
     *     summary="Update an existing category product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category Product updated successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "success",
     *                 "message": "Category Product updated successfully",
     *                 "data": {
     *                     "id": 1,
     *                     "name": "Updated Category"
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
     *                 "message": "Category Product not found"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "failed",
     *                 "message": "update Category Product failed!",
     *                 "errors": {
     *                     "name": "array of error",
     *                 }
     *             }
     *         )
     *     )
     * )
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


    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/category-products/{id}",
     *     tags={"Category Products"},
     *     operationId="deleteCategoryProduct",
     *     summary="Delete a category product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category Product deleted successfully",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "success",
     *                 "message": "Category Product deleted successfully!"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category Product not found",
     *         @OA\JsonContent(
     *             example={
     *                 "status": "failed",
     *                 "message": "Category Product not found"
     *             }
     *         )
     *     )
     * )
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
