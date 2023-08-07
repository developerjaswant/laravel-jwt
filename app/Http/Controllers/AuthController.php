<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api',['except'=>['login','register']]);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
      
        if (!$token=auth()->attempt($validator->validated())) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 404);
        }

        return $this->createNewToken($token);   
    }
        public function createNewToken($token){
            return response()->json([
                    'access_token'=>$token,
                    'token_type'=>'bearer',
                    'expires_in'=>auth()->factory()->getTTL()*60,
                    'user'=>auth()->user()

            ]);
        }
        public function profile(){
            return response()->json(auth()->user());  
        }
        public function logout(){
            auth()->logout(); 
            return response()->json([
                'message' => 'User logged out'
            ]);
        }
    
}
