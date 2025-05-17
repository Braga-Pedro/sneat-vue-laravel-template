<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UserModule;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $admins = UserModule::select([
            'user_modules.*',
            'users.name',
            'users.email',
            'users.phone',
            'users.address',
            'users.deleted_at',
            'users.created_at',
            'users.updated_at'
        ])
        ->join('users', 'users.id', '=', 'user_modules.user_id')
        ->withTrashed()
        ->orderBy('users.updated_at', 'desc');

        return $this->handleCollectionOrPaginationReturn($admins);
    }

    public function operatorUsers()
    {
        $operators = UserModule::whereNull('deleted_at')
        ->with('user')
        ->where('operator', true)
        ->get();

        return response()->json([
            'operators' => $operators
        ]);
    }

    public function findByArgument(Request $request)
    {
        $admins = UserModule::select([
            'user_modules.*',
            'users.name',
            'users.email',
            'users.phone',
            'users.address',
            'users.deleted_at',
            'users.updated_at',
            'users.updated_at'
        ])
        ->join('users', 'users.id', '=', 'user_modules.user_id')
        ->withTrashed()
        ->where(function ($query) use ($request) {
            $argument = strtolower($request->get('argument'));
            $query->where('users.email', 'like', "%{$argument}%")
                ->orWhere('users.name', 'ilike', "%{$argument}%")
                ->orWhere('users.phone', 'ilike', "%{$argument}%");
        })
        ->orderBy('users.created_at', 'desc');

        return $this->handleCollectionOrPaginationReturn($admins);
    }

    public function create()
    {
            
    }

    public function store(Request $request)
    {
        $getUser = $request->get('user');
        DB::beginTransaction();
        try {
            $getUser['email'] = strtolower($getUser['email']);

            $uniqueEmail = User::where('email', $getUser['email'])->first();

            if ($uniqueEmail) {
                return response()->json([
                    'title' => 'Ops!!',
                    'msg' => 'Este email já está em uso.',
                    'type' => 'error',
                ], 400);
            }
            // $password = Str::random(8);
            
            $user = User::firstOrCreate(
                [
                    'email' => $getUser['email']
                ],
                [
                    'name' => $getUser['name'],
                    'password' => bcrypt($getUser['password']),
                    'phone' => $getUser['phone'] ?? null,
                    'address' => $getUser['address'] ?? null,
                ]
            );

            if ($user->userModule) {
                $user->userModule->update(
                    ['admin' => true]
                );
            } else {
                $user->userModule()->create([
                    'uuid' => Str::uuid(),
                    'admin' => $getUser['admin'] ?? false,
                    'operator' => $getUser['operator'] ?? false,
                ]);
            }

            // $user->sendNewAdminNotification($password);

            DB::commit();
            return response()->json([
                'title' => 'Sucesso!',
                'msg' => 'Administrador adicionado com sucesso!',
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

    public function show(UserModule $admin)
    {
        $admin->load(['user']);

        return response()->json($admin);
    }

    public function edit(string $id)
    {
            
    }

    public function update(Request $request, UserModule $admin)
    {
        DB::beginTransaction();
        try {
            // Atualizar os dados do usuário relacionado (users)
            if ($request->has('user')) {
                $admin->user->update($request->input('user'));
            }

            // Atualizar os dados do próprio UserModule
            if ($request->has('userModule')) {
                $admin->update($request->input('userModule'));
            }            

            DB::commit();
            return response()->json([
                'title' => 'Sucesso!',
                'msg' => 'Administrador atualizado com sucesso!',
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

    public function activate($uuid)
    {
        $userModule = UserModule::withTrashed()->where('uuid', $uuid)->first();

        $user = User::withTrashed()->where('id', $userModule->user_id);

        DB::beginTransaction();
        try {
            $userModule->update([
                'deleted_at' => null
            ]);


            $user->update([
                'deleted_at' => null
            ]);

            DB::commit();
            return response()->json([
                'title' => 'Sucesso!',
                'msg' => 'Administrador ativado com sucesso!',
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

    public function destroy(UserModule $admin)
    {
        DB::beginTransaction();
        try {
            $admin->user->update([
                'deleted_at' => now()
            ]);

            $admin->update([
                'deleted_at' => now()
            ]);

            DB::commit();
            return response()->json([
                'title' => 'Sucesso!',
                'msg' => 'Administrador excluído com sucesso!',
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
}
