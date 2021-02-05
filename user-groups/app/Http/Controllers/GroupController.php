<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupUser;
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
        $this->fractal = new Manager();
    }

    /**
     * Show all groups
     *
     * @return void
     */
    public function index()
    {
        $paginator = Group::paginate();
        $groups = $paginator->getCollection();
        $resource = new Collection($groups, new GroupTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * Show group by id
     *
     * @param int $id id of the group
     *
     * @return void
     */
    public function showGroup($id)
    {
        //show group deatils
        try {
            $group = Group::findOrFail($id);
            $resource = new Item($group, new GroupTransformer());
            return $this->fractal->createData($resource)->toArray();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid group!'], 404);
        }
    }

    /**
     * Add members to group
     *
     * @param Request $request user ids
     * @param int $id id of group
     *
     * @return void
     */
    public function addMembers(Request $request, $id)
    {
        $this->validate($request, [
            'member_id' => 'required',
        ]);
        $user_id = Auth::user()->id;
        $check_group = Group::find($id);
        /**
         * check group is public or private
         */
        if ($check_group->type == 'private' && $user_id != $check_group->created_by) {
            return response()->json(['message' => 'Only Group creator can add members.'], 401);
        }
        /**
         * member_id is string of user_ids to add in this group
         */
        $user_ids = explode(',', $request->member_id);
        $data = [];
        foreach ($user_ids as $member) {
            $check_member_exists = GroupUser::where(['group_id' => $id, 'user_id' => $member])->first();
            //check if member already added to the group
            if (!$check_member_exists) {
                $data[] = ['group_id' => $id, 'user_id' => $member, 'created_at' => time(), 'updated_at' => time()];
            }
        }
        /**
         * insert into group_has_member table(add members to a group)
         */
        $group_members = GroupUser::insert($data);
        if ($group_members) {
            $members = Group::find($id)->members;
            $userdata = [];
            foreach ($members as $member) {
                $userdata[] = User::find($member->user_id);
            }
            $resource = new Collection($userdata, new UserTransformer());
            return $this->fractal->createData($resource)->toArray();
        }
        return response()->json(['message' => 'Failed to add members!'], 400);
    }

    /**
     * Show all group members
     *
     * @param int $id id of group
     *
     * @return void
     */
    public function showGroupMembers($id)
    {
        $group_members = Group::find($id)->members;
        $userdata = [];
        /**
         * get all user_id of from group_user table
         */
        foreach ($group_members as $member) {
            $userdata[] = User::find($member->user_id);
        }
        $resource = new Collection($userdata, new UserTransformer());
        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * Remove members from group
     *
     * @param int $id id of group
     * @param int $member_ids id of users to be removed from group
     *
     * @return void
     */
    public function removeGroupMember($id, $member_ids)
    {
        //user_id is string of user ids remove from this group
        $user_ids = explode(',', $member_ids);
        GroupUser::whereIn('user_id', $user_ids)->where('group_id', $id)->delete();
        return response()->json(['message' => 'Deleted successfully!'], 410);
    }

    /**
     * Create new group
     *
     * @param Request $request
     *
     * @return void
     */
    public function create(Request $request)
    {
        $user_id = Auth::user()->id;
        $this->validate($request, [
            'name' => 'required|unique:groups',
            'type' => 'required',
        ]);
        $group = Group::create($request->all());
        $group->created_by = $user_id;
        $group->save();
        $resource = new Item($group, new GroupTransformer());
        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * Update group by id
     *
     * @param int $id id of group
     * @param Request $request
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        $group = Group::findOrFail($id);
        $group->update($request->all());
        return response()->json($group, 200);
    }

    /**
     * Delete group by id
     *
     * @param int $id id of group
     *
     * @return void
     */
    public function delete($id)
    {
        Group::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
