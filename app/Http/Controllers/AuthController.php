<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints for user authentication"
 * )
 */
class AuthController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Post(
     *      path="/register",
     *      operationId="register",
     *      tags={"Authentication"},
     *      summary="Register a new user",
     *      description="Register a new user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password"},
     *              @OA\Property(property="name", type="string", format="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="string", example="bloodykheeng@gmail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="secret"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="user", type="object",
     *                      @OA\Property(property="name", type="string", example="John Doe"),
     *                      @OA\Property(property="email", type="string", example="bloodykheeng@gmail.com"),
     *                  ),
     *                  @OA\Property(property="token", type="string", example="your_access_token_here"),
     *              ),
     *          ),
     *      )
     * )
     */
    public function register(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken,
        ]);
    }

    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="login",
     *      tags={"Authentication"},
     *      summary="Login",
     *      description="Log in a user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", format="string", example="bloodykheeng@gmail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="secret"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="user", type="object",
     *                      @OA\Property(property="name", type="string", example="John Doe"),
     *                      @OA\Property(property="email", type="string", example="bloodykheeng@gmail.com"),
     *                  ),
     *                  @OA\Property(property="token", type="string", example="your_access_token_here"),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=401),
     *              @OA\Property(property="error", type="string", example="Credentials do not match"),
     *          ),
     *      )
     * )
     */
    public function login(LoginUserRequest $request)
    {
        $validatedData = $request->validated();
        
        if (!Auth::attempt($validatedData)) {
            return $this->error('', 'Credentials do not match', 401);
        }
        
        $user = Auth::user();
        
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Api Token of ' . $user->name)->plainTextToken,
        ]);
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logout",
     *      tags={"Authentication"},
     *      summary="Logout",
     *      description="Log out the currently authenticated user",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="integer", example=200),
     *              @OA\Property(property="message", type="string", example="You have successfully logged out and your token has been deleted"),
     *          ),
     *      )
     * )
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success(['message' => 'You have successfully logged out and your token has been deleted']);
    }
}
