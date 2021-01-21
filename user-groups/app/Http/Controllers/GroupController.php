<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupHasUser;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function showAllGroups()
    {
        // $user = Auth::user()->get();
        // return response()->json(['status' => 'success', 'result' => $user]);

        return response()->json(Group::all());
    }

    public function showGroup($id)
    {
        return response()->json(Group::find($id));
    }

    public function addMembers(Request $request, $id)
    {
        $this->validate($request, [
            'member_id' => 'required',
        ]);
        $member_ids = explode(',', $request->member_id);
        $data = [];
        foreach ($member_ids as $member) {
            $data[] = ['group_id' => $id, 'member_id' => $member, 'created_at' => date('Y-m-d H:i:s')];
        }
        $group_members = GroupHasUser::insert($data);
        if ($group_members) {
            $group_members = GroupHasUser::where('group_id', $id)->pluck('member_id')->toArray();
            $group = Group::where('id', $id)->first();

            $members = User::whereIn('id', $group_members)->get();
            $userdata = [];
            foreach ($members as $member) {
                $userdata[] = ['id' => $member->id, 'name' => $member->name];
            }
            $groupData = ['group_name' => $group->group_name, 'group_created_by' => $group->group_created_by, 'memebers' => $userdata];

        }
        return response()->json($groupData, 201);
    }

    public function showGroupMembers(Request $request, $id)
    {
        $group_members = GroupHasUser::where('group_id', $id)->pluck('member_id')->toArray();
        $group = Group::where('id', $id)->first();

        $members = User::whereIn('id', $group_members)->get();
        $userdata = [];
        foreach ($members as $member) {
            $userdata[] = $member->name;
        }
        $data = ['group_name' => $group->group_name, 'group_created_by' => $group->group_created_by, 'memebers' => $userdata];
        return response()->json($data);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'group_name' => 'required|unique:groups',
            'group_created_by' => 'required',
        ]);
        //dd($request->password);
        $group = Group::create($request->all());

        return response()->json($group, 201);
    }

    public function update($id, Request $request)
    {
        $group = Group::findOrFail($id);
        $group->update($request->all());

        return response()->json($group, 200);
    }

    public function delete($id)
    {
        Group::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
