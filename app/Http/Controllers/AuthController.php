<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * Post a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     *    @OA\Post(
     *       path="/register",
     *       tags={"Authentication"},
     *       operationId="registration",
     *       summary="Register Account",
     *       description="Create new account for accessing data",
     *       @OA\RequestBody(
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@email.com", "password": "test1234"}
     *             )
     *         )
     *     ),
     *       @OA\Response(
     *           response="201",
     *           description="Ok",
     *           @OA\JsonContent
     *           (example={
     *              "status": "success",
     *              "message": "User created successfully",
     *              "user": {
     *                  "name": "hab",
     *                  "email": "hab@email.com",
     *                  "updated_at": "2024-08-10T09:54:57.000000Z",
     *                  "created_at": "2024-08-10T09:54:57.000000Z",
     *                  "id": 1
     *              },
     *              "authorization": {
     *                  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3JlZ2lzdGVyIiwiaWF0IjoxNzIzMjgzNjk3LCJleHAiOjE3MjMyODcyOTcsIm5iZiI6MTcyMzI4MzY5NywianRpIjoiTXpKdjQ4b2RkRGpFSFM4RCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.UcjMqKzsI58tcHvtC23s6fGD8xIrqTTCMUEtrg9RnsM",
     *                  "type": "bearer"
     *              }
     *          }),
     *      ),
     *      @OA\Response(
     *           response="422",
     *           description="Unprocessable Content",
     *       ),
     *  )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "failed",
                "message" => "Registration failed",
                "errors" => $validator->errors()
            ], 422);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    /**
     * Post a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     *    @OA\Post(
     *       path="/login",
     *       tags={"Authentication"},
     *       operationId="login",
     *       summary="Login Account",
     *       description="Login to account",
     *       @OA\RequestBody(
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@email.com", "password": "test1234"}
     *             )
     *         )
     *     ),
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent
     *           (example={
     *              "status": "success",
     *              "message": "User created successfully",
     *              "user": {
     *                  "name": "hab",
     *                  "email": "hab@email.com",
     *                  "updated_at": "2024-08-10T09:54:57.000000Z",
     *                  "created_at": "2024-08-10T09:54:57.000000Z",
     *                  "id": 1
     *              },
     *              "authorization": {
     *                  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3JlZ2lzdGVyIiwiaWF0IjoxNzIzMjgzNjk3LCJleHAiOjE3MjMyODcyOTcsIm5iZiI6MTcyMzI4MzY5NywianRpIjoiTXpKdjQ4b2RkRGpFSFM4RCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.UcjMqKzsI58tcHvtC23s6fGD8xIrqTTCMUEtrg9RnsM",
     *                  "type": "bearer"
     *              }
     *          }),
     *      ),
     *      @OA\Response(
     *           response="401",
     *           description="Unauthorized",
     *       ),
     *      @OA\Response(
     *           response="422",
     *           description="Unprocessable Content",
     *       ),
     *  )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email:rfc",
            "password" => "required|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "failed",
                "message" => "Login failed",
                "errors" => $validator->errors()
            ], 422);
        }

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                "status" => "failed",
                "message" => "wrong email or password"
            ], 401);
        }

        return response()->json([
            "status" => "success",
            "message" => "Login Success",
            "data" => [
                "user" => auth()->user(),
                "token" => $token
            ]
        ], 200);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     *    @OA\Get(
     *       path="/me",
     *       tags={"Authentication"},
     *       operationId="me",
     *       summary="User Info",
     *       description="Get detail user",
     *       security={{"bearerAuth":{}}},
     *       @OA\Response(
     *           response="200",
     *           description="Ok",
     *           @OA\JsonContent
     *           (example={
     *              "status": "success",
     *              "message": "User created successfully",
     *              "user": {
     *                  "name": "hab",
     *                  "email": "hab@email.com",
     *                  "updated_at": "2024-08-10T09:54:57.000000Z",
     *                  "created_at": "2024-08-10T09:54:57.000000Z",
     *                  "id": 1
     *              },
     *          }),
     *      ),
     *      @OA\Response(
     *           response="401",
     *           description="Unauthorized",
     *       ),
     *  )
     */
    public function me()
    {
        return response()->json([
            "status" => "success",
            "message" => "User found",
            "data" => [
                "user" => auth()->user(),
            ]
        ], 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Log the user out",
     *     description="Invalidate the token.",
     *     operationId="logout",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent
     *              (example={
     *                  "status": "success",
     *                  "message": "Successfully logged out",
     *              }),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @OA\Get(
     *     path="/refresh",
     *     summary="Refresh the token",
     *     description="Generate a new token.",
     *     operationId="refreshToken",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ], 200);
    }
}
