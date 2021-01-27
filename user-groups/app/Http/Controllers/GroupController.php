<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupHasMember;
use App\Http\Controllers\Controller;
use App\Transformers\GroupTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class GroupController extends Controller
{
    private $fractal;
    public function __construct()
    {
        //$this->middleware('auth');
        $this->fractal = new Manager();
    }

    public function index()
    {
        //show all groups
        $paginator = Group::paginate();
        $groups = $paginator->getCollection();
        $resource = new Collection($groups, new GroupTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->fractal->createData($resource)->toArray();
    }

    public function showGroup($id)
    {
        //show group deatils
        try {
            $group = Group::findOrFail($id);
            $resource = new Item($group, new GroupTransformer);
            return $this->fractal->createData($resource)->toArray();

        } catch (\Exception $e) {

            return response()->json(['message' => 'Invalid group!'], 404);
        }
    }

    public function addMembers(Request $request, $id)
    {
        $this->validate($request, [
            'member_id' => 'required',
        ]);
        $user_id = Auth::user()->id;
        $check_group_creator = Group::where(['id' => $id, 'group_created_by' => $user_id])->first();
        if ($check_group_creator == null) {
            return response()->json(['message' => 'Only Group creator can add members.'], 401);
        }
        //member_id is string of user_ids to add in this group
        $member_ids = explode(',', $request->member_id);
        $data = [];
        foreach ($member_ids as $member) {
            $check_member_exists = GroupHasMember::where(['group_id' => $id, 'member_id' => $member])->first();
            //check if member already added to the group
            if (!$check_member_exists) {
                $data[] = ['group_id' => $id, 'member_id' => $member, 'created_at' => date('Y-m-d H:i:s')];
            }
        }
        //insert into group_has_member table(add members to a group)
        $group_members = GroupHasMember::insert($data);
        if ($group_members) {
            $members = Group::find($id)->members;
            $userdata = [];
            foreach ($members as $member) {
                $userdata[] = User::find($member->member_id);
            }
            $resource = new Collection($userdata, new UserTransformer);
            return $this->fractal->createData($resource)->toArray();
        }
        return response()->json(['message' => 'Failed to add members!'], 400);
    }

    public function showGroupMembers($id)
    {
        //show all members of group
        $group_members = Group::find($id)->members;
        $userdata = [];
        //get all member_id of from group_has_members
        foreach ($group_members as $member) {
            $userdata[] = User::find($member->id);
        }
        $resource = new Collection($userdata, new UserTransformer);
        return $this->fractal->createData($resource)->toArray();
    }

    public function removeGroupMember($id, $member_ids)
    {
        //member_id is string of user ids remove from this group
        $member_ids = explode(',', $member_ids);
        GroupHasMember::whereIn('member_id', $member_ids)->where('group_id', $id)->delete();
        return response()->json(['message' => 'Deleted successfully!'], 410);
    }

    public function joinGroup(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        try {
            //check member already exists in this group or not
            $userExist = GroupHasMember::where(['member_id' => $user_id, 'group_id' => $id])->first();
            if ($userExist) {
                $data = ['message' => 'User already exists in this group'];
            } else {
                $data = GroupHasMember::create(['group_id' => $id, 'member_id' => $user_id, 'joined_by' => 'user']);
            }
            return response()->json($data, 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unathorized'], 404);
        }
        return response()->json($data, 201);
    }

    public function create(Request $request)
    {
        $user_id = Auth::user()->id;
        $this->validate($request, [
            'group_name' => 'required|unique:groups',
        ]);
        $group = Group::create($request->all());
        $group->group_created_by = $user_id;
        $group->save();
        $resource = new Item($group, new GroupTransformer);
        return $this->fractal->createData($resource)->toArray();
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
