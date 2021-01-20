<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->input('email'))->first();
        if (Hash::check($request->input('password'), $user->password)) {
            $apikey = base64_encode(Str::random(40));
            User::where('email', $request->input('email'))->update(['api_token' => $apikey]);
            return response()->json(['status' => 'success', 'api_token' => $apikey]);
        } else {
            return response()->json(['status' => 'fail'], 401);
        }
    }

    public function showAllUsers()
    {
        return response()->json(User::all());
    }

    public function showUser($id)
    {
        return response()->json(User::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'contact_no' => 'required|unique:users',
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
