<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UserModule;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'=> 'required|string|email',
            'password'=> 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'error'=> ['email' => 'Unauthorized']
            ],401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'tokenType' => 'Bearer',
            'dataUser' => $user,       
            'dataModule' => UserModule::where('user_id', $user->id)->get()->toArray(),
            'selectedModule' => UserModule::where('user_id', $user->id)->first()->amount == 1 ? UserModule::where('user_id', $user->id)->first()->only_one_display : ''
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string',
            'repeat_password' => 'required|same:password'
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'  => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
          
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            DB::commit();
            return response()->json([
              'message' => 'Usuário criado com sucesso!',
              'accessToken' => $token,
            ], 201);
        } catch (Exception $exception) {
            DB::rollback();
            return response()->json([
                'title' => 'Ops!!',
                'msg' => 'Credenciais fornecidas inválidas!',
                'type' => 'error',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
  
        return response()->json([
          'message' => 'Logout feito com sucesso!'
        ]);
    }

    public function changePassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $password = $request->get('password');
            $confirmPassword = $request->get('confirm_password');
            $current_password = $request->get('current_password');
            
            if ($password == null || strlen($password) < 8 || $password != $confirmPassword || !Hash::check($current_password, auth()->user()->password)) {
                return response()->json([
                  'title' => 'Ops!!',
                  'msg' => 'Senha não atende aos requisitos!',
                  'type' => 'error',
                ], 400);
            } 

            auth()->user()->update([
                'password' => bcrypt($request->get('password'))
            ]);
            
            DB::commit();
            return response()->json([
              'title' => 'Sucesso!',
              'msg' => 'Senha alterada com sucesso!',
              'type' => 'success',
            ]);
        } catch (Exception $exception) {
          DB::rollBack();
          return response()->json([
            'title' => 'Ops!!',
            'msg' => $exception->getMessage(),
            'type' => 'error',
          ], 400);
        }
    }

    public function selectModule(Request $request)
    {
        $userModule = $request->get('module');

        if (!$request->user()->userModule->{$userModule}) {
          return response()->json(['error' => 'Module Unauthorized'], 401);
        
        } else {
          session()->put('selected_module', $userModule);
          
          return response()->json([
            'message' => 'Modulo selecionado com Sucesso!'
          ]);
        }
    }    

}
