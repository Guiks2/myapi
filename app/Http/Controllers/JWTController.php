<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/authenticate",
     *     summary="Get authentification token",
     *     description="Use this method to return a new authentification token.",
     *     operationId="getAuthentification",
     *     consumes={"multipart/form-data", "application/x-www-form-urlencoded"},
     *     tags={"authenticate"},
     *     @SWG\Parameter(
     *          name="email",
     *          in="formData",
     *          type="string",
     *          required=true,
     *          description="Identification email",
     *          format="string"
     *      ),
     *     @SWG\Parameter(
     *          name="password",
     *          in="formData",
     *          type="string",
     *          required=true,
     *          description="Identification password",
     *          format="string"
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *               type="string",
     *           ),
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Invalid email or password",
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Could not create the token",
     *     ),
     * )
     */
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(
            compact('token'),
            200);
    }

    /**
     * @SWG\Post(
     *     path="/hashPassword",
     *     summary="Get hashed password",
     *     description="Use this method to return a hashed password",
     *     operationId="getHashedPassword",
     *     tags={"authenticate"},
     *     @SWG\Parameter(
     *          name="password",
     *          in="formData",
     *          type="string",
     *          required=true,
     *          description="Password to hash",
     *          format="string"
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="Successful operation",
     *          @SWG\Schema(
     *               type="string",
     *           ),
     *     ),
     * )
     */
    public function hashPassword(Request $request){
        $password = $request->password;
        $hashedPassword = Hash::make($password);
        return response()->json(
            $hashedPassword,
            200);
    }
}
