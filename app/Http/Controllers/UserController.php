<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function getMany()
    {
        return response()->json(User::get());
    }

    public function get(User $user)
    {
        return response()->json($user);
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|bail|min:2',
                'email' => 'required|bail|email|unique:users',
                'password' => 'required|bail|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->email),
            ]);

            return response()->json($user);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }

    public function edit(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'bail|min:2',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 401);
            }

            if ($request->has('name')) {
                $user->name = $request->name;
                $user->save();
            }

            return response()->json([
                'updated' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }


    public function remove(User $user)
    {
        try {
            $user->delete();

            return response()->json(['removed' => true]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }
}
