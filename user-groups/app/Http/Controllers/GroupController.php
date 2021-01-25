<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupHasMember;
use App\Http\Controllers\Controller;
use App\Transformers\GroupTransformer;
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
        $this->middleware('auth');
        $this->fractal = new Manager();

    }

    public function index()
    {
        $paginator = Group::paginate();
        $groups = $paginator->getCollection();
        $resource = new Collection($groups, new GroupTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->fractal->createData($resource)->toArray();

        //return response()->json(Group::all());
    }

    public function showGroup($id)
    {
        try {
            $group = Group::findOrFail($id);
            $resource = new Item($group, new GroupTransformer);
            return $this->fractal->createData($resource)->toArray();
            //return response()->json(Group::find($id), 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'Invalid group!'], 404);
        }
    }

    public function addMembers(Request $request, $id)
    {
        $this->validate($request, [
            'member_id' => 'required',
        ]);
        //member_id is string of user ids to add in this group
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
        $user_id = Auth::user()->id;
        try {
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
        $group = Group::create(['group_name' => $request->group_name, 'group_created_by' => $user_id]);

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
