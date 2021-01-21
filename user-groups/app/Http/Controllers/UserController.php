<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function showAllUsers(Request $request)
    {
        $name = $request->get('name');
        if ($name) {
            $users = User::where('name', 'like', "%{$name}%")->get();
        } else {
            $users = User::all();
        }
        return response()->json($users);
    }

    public function showUser($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['user' => $user], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'user not found!'], 404);
        }
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'contact_no' => 'required|unique:users',
            'password' => 'required',
        ]);
        //dd($request->password);
        $user = User::create($request->all());
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json($user, 201);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user, 200);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
