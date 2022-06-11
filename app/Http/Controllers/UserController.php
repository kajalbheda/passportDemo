<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;


class UserController extends Controller
{
    public function registration(Request $req){
    
        $input = $req->only(['name', 'email', 'password']);

        $validate_data = ['name'=>"required",
        'email'=>"required|email",
        'password'=>"required",
       ];

        $validator = Validator::make($input, $validate_data);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(),202);
        }

        
        $allData=$req->all();
        $allData['password']=bcrypt($allData['password']);

        $user=User::create($allData);

        // $resArr=[];
        // $resArr['token']=$user->createToken('api-token')->accessToken;
        // $resArr['name']=$user->name;

        return response()->json([
            'success' => true,
            'message' => 'User registered succesfully, Use Login method to receive token.'
        ], 200);

    }
    public function login(Request $req){
        if(Auth::attempt([
            'email'=>$req->email,
            'password'=>$req->password
        ])){
            $user=Auth::user();
            $resArr=[];
            $resArr['token']=$user->createToken('api-token')->accessToken;
            $resArr['name']=$user->name;
            return response()->json($resArr,200);
        }
        else{
            return response()->json(['error'=>'Unauthorized Access'],203);
        }
    }

    public function getdata(){
        return User::all();
    }

    public function userDetail()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully.',
            'data' => auth()->user()
        ], 200);
    }
    
    public function logout()
    {
        $access_token = auth()->user()->token();

        // logout from only current device
        $tokenRepository = app(TokenRepository::class);
        $tokenRepository->revokeAccessToken($access_token->id);

        // use this method to logout from all devices
        // $refreshTokenRepository = app(RefreshTokenRepository::class);
        // $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($$access_token->id);

        return response()->json([
            'success' => true,
            'message' => 'User logout successfully.'
        ], 200);
    }
}
