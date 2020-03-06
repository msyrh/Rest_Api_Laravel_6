<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public $statusBerhasil = 200;

    public function login()
    {
        if(Auth::attempt(['email'=>request('email'),'password'=>request('password')])){
            $pengguna=Auth::user();
            $success['token']=$pengguna->createToken('nApp')->accessToken;
            return response()->json(['success'=>$success], $this->statusBerhasil);
        }
        else{
            return response()->json(['error'=>'Unauthorised'],401);
        }
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()],401);
        }
        $data_masuk=$request->all();
        $data_masuk['password']=bcrypt($data_masuk['password']);
        $pengguna = User::create($data_masuk);
        $success['token']=$pengguna->createToken('nApp')->accessToken;
        $success['name']=$pengguna->name;
        return response()->json(['success'=>$success],$this->statusBerhasil);
    }
    public function details()
    {
        $pengguna=Auth::user();
        return response()->json(['success'=>$pengguna],$this->statusBerhasil);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $response=[
            'success'=>true,
            'message'=>'Selamat Geh Jenengan berhasil keluar'
        ];
        return response()->json($response,200);
    }
}
