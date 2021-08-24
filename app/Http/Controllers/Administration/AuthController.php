<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $params =json_decode($request->all()['json'],true);
        $credentials = ['email'=>$params['email'],'password'=>$params['password']];
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $response = array('data'=>$token,'message'=>'Bienvenido','status'=>200);
        return json_encode($response);
    }

    public function getAuthenticatedUser()
    {
        $response = array('status'=>404);
        try {
            JWTAuth::factory()->setTTL(720);
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                $response['message'] = 'Usuario no encontrado';
                return json_encode($response);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $response['message'] = 'Token caducado';
                return json_encode($response);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $response['message'] = 'Token no válido';
                return json_encode($response);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response['message'] = 'Token no encontrado';
                return json_encode($response);
        }
        $response['status'] = 200;
        $response['message'] = 'Usuario logeado';
        $response['data'] = $user;
        return json_encode($response);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }
}
