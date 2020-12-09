<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class AuthController extends Controller
{
    /**
    * @OA\Post(
    *       path="/api/auth/signin",
    *       summary="Login",
    *       tags={"Auth"},
    *       description="Authenticate a user with email and password",
    *       operationId="login",
    *       @OA\requestBody(
    *           required=true,
    *           description="User credentials",
    *           @OA\JsonContent(
    *               @OA\Property(property="email", type="string", format="email", example="employee@example.com"),
    *               @OA\Property(property="password", type="string", format="password", example="employee")        
    *           )
    *       ),
    *       @OA\Response(
    *           response=200,
    *           description="Logged in",
    *           @OA\JsonContent(
    *               @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXBpXC9hdXRoXC9zaWduaW4iLCJpYXQiOjE2MDcyMDc1MTQsImV4cCI6MjIwNzIwNzQ1NCwibmJmIjoxNjA3MjA3NTE0LCJqdGkiOiJoTk9wbk91WUNzTU1PSTVCIiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.LnjcvQDOegH5XlJTpXNJXyl4nye1F5mac-X8eoYrflk"),
    *               @OA\Property(property="token_type", type="string", example="bearer"),
    *               @OA\Property(property="expires_in", type="integer", example=86400)
    *           )
    *       ),
    *       @OA\Response(
    *           response=401,
    *           description="Invalid credentials",
    *           @OA\JsonContent(
    *               @OA\Property(property="message", type="string", example="The provided credentials are incorrect.")
    *           )
    *       ),
    *       @OA\Response(response=422, ref="#/components/responses/invalid-data")
     * )
    */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            return response()->json(compact('token'));
    }

    
    /**
     * @OA\Post(
     *      path="/api/auth/signout",
     *      tags={"Auth"},
     *      summary="Logout",
     *      description="Logout current user",
     *      operationId="logout",
     *      @OA\Response(
     *          response=200,
     *          description="Logout response",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", example="Successfully logged out")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          ref="#/components/responses/unauthenticated"
     *      )
     * )
    */
    public function signOut(Request $request){

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

     /**
     * @OA\Get(
     *      path="/api/auth/currentuser",
     *      tags={"Auth"},
     *      summary="User informations",
     *      description="Get current user email and first name",
     *      operationId="currentUser",
     *      @OA\Response(
     *          response=200,
     *          description="User email and name information",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/user"
     *          )
     *      ),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated")
     * )
    */
    public function currentUser(Request $request){

        $user = $request->user();
        $role = $user->getRoleNames()->first() ? $user->getRoleNames()->first() : 'Employee';
        return response()->json([
            'email' => $user->email,
            'name' => $user->firstName,
            'role' => $role,
            'id' => $user->id,
            'permissions' => PermissionResource::collection($user->getAllPermissions()) 
        ]);
        return new UserResource(auth()->user());
    }

     /**
     * @OA\Get(
     *      path="/api/auth/user",
     *      tags={"Auth"},
     *      summary="User informations",
     *      description="Get current user informations",
     *      operationId="currentUser",
     *      @OA\Response(
     *          response=200,
     *          description="All User information",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/user"
     *          )
     *      ),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated")
     * )
    */
    public function getAuthenticatedUser(Request $request){
        
        $user = $request->user();

        try{
            
            if(! $user = JWTAuth::parseToken()->authenticate()){
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
        }
}
