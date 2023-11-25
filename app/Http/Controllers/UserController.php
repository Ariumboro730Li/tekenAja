<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Models\UserRole;
use App\Services\User\DatatableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin-only');
    }

    public function page()
    {
        return view('pages.users');
    }

    public function rolesList(){
        $data = UserRole::select('id', 'role_name')->get()->toArray();
        return response()->json($data);
    }

    public function datatable(){
        return app(DatatableService::class)->applyQueryBuilder()->applyActions()->editColumnsYajra()->getResultWithMessage();
    }

    public function store(UserRequest $request){
        $data = $request->validated();
        $user = [];
        DB::transaction(function () use ($data, &$user) {
            $user = User::create([
                'name' => ucwords($data['name']),
                'email' => $data['email'],
                'role_id' => $data['role_id'],
                'password' => Hash::make($data['password']),
            ]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been saved',
            'data' => $user,
        ]);
    }
    public function fetchById($id){
        $data = DB::table('users')->where("id", $id)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Data has been fetched',
            'data' => $data,
        ]);
    }

    public function update(UserUpdateRequest $request){
        $user = [];
        DB::transaction(function () use ($request, &$user) {
            $find = User::find($request->id);
            if($find){
                $user = User::where('id', $request->id)->update([
                    'name' => ucwords($request->name),
                    'email' => $request->email,
                    'role_id' => $request->role_id,
                    'password' => Hash::make($request->password ?? $find->password),
                ]);
            }
        });

        if($user){
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been updated',
                'data' => $user,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
                'data' => $user,
            ], 404);
        }
    }

    public function delete($id){
        $user = [];
        DB::transaction(function () use ($id, &$user) {
            $find = User::find($id);
            if($find){
                $user = User::where('id', $id)->delete();
            }
        });

        if($user){
            return response()->json([
                'status' => 'success',
                'message' => 'User has been disabled',
                'data' => $user,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'data' => $user,
            ], 404);
        }
    }

    public function activate($id){
        $user = [];
        DB::transaction(function () use ($id, &$user) {
            $find =  DB::table('users')->where("id", $id)->first();
            if($find){
                $user =  DB::table('users')->where("id", $id)->update([
                    'deleted_at' => null,
                ]);
            }
        });

        if($user){
            return response()->json([
                'status' => 'success',
                'message' => 'User is Active now',
                'data' => $user,
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'data' => $user,
            ], 404);
        }
    }
}
