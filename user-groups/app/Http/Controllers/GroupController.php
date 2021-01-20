<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function showAllGroups()
    {
        return response()->json(Group::all());
    }

    public function showGroup($id)
    {
        return response()->json(Group::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'group_name' => 'required|unique:groups',
            'group_created_by' => 'required',
        ]);
        //dd($request->password);
        $user = Group::create($request->all());

        return response()->json($user, 201);
    }

    public function update($id, Request $request)
    {
        $user = Group::findOrFail($id);
        $user->update($request->all());

        return response()->json($user, 200);
    }

    public function delete($id)
    {
        Group::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
