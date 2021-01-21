<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupHasMember;
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
        $group_members = GroupHasMember::insert($data);
        if ($group_members) {
            $group_members = GroupHasMember::where('group_id', $id)->pluck('member_id')->toArray();
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
        $group_members = GroupHasMember::where('group_id', $id)->pluck('member_id')->toArray();
        $group = Group::where('id', $id)->first();

        $members = User::whereIn('id', $group_members)->get();
        $userdata = [];
        foreach ($members as $member) {
            $userdata[] = $member->name;
        }
        $data = ['group_name' => $group->group_name, 'group_created_by' => $group->group_created_by, 'memebers' => $userdata];
        return response()->json($data);
    }

    public function removeGroupMember(Request $request, $id, $member_ids)
    {
        $member_ids = explode(',', $member_ids);
        GroupHasMember::whereIn('member_id', $member_ids)->where('group_id', $id)->delete();
        return response('Deleted Successfully', 200);
    }

    public function joinGroup(Request $request, $id)
    {
        $this->validate($request, [
            'user_id' => 'required',
        ]);
        $data = [];
        //dd($request->user_id);
        $userExist = GroupHasMember::where(['member_id' => $request->user_id, 'group_id' => $id])->first();
        if ($userExist) {
            $data = ['msg' => 'User already exists in this group'];
        } else {
            $data = GroupHasMember::create(['group_id' => $id, 'member_id' => $request->user_id, 'joined_by' => 'user']);

        }

        return response()->json($data, 201);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'group_name' => 'required|unique:groups',
            'group_created_by' => 'required',
        ]);
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
