<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //验证注册字段
        Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6']
        ])->validate();

        //在数据库中创建用户并返回包含 api_token 字段的用户数据
        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'api_token' => Str::random(60)
        ]);

    }

    public function login(Request $request)
    {
        //验证登录字段
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email',$email)->first();
        //校验成功返回 Token 信息
        if ($user && Hash::check($password, $user->password)){
            return response()->json(['user'=>$user, 'success'=>true]);
        }
        return response()->json(['success'=>false]);
    }
}
