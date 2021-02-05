<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class UserController extends Controller
{
    private $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }

    /**
     * Show all users
     *
     * @param Request $request
     *
     * @return void
     */
    public function index(Request $request)
    {
        //filter user by passing query string
        $name = $request->get('name');
        if ($name) {
            $paginator = User::where('name', 'like', "%{$name}%")->paginate();
        } else {
            $paginator = User::paginate();
        }
        $users = $paginator->getCollection();
        $resource = new Collection($users, new UserTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return $this->fractal->createData($resource)->toArray();
    }

    /**
     * Show user by id
     *
     * @param int $id id of user
     *
     * @return void
     */
    public function show($id)
    {
        //fetch user data by userId
        try {
            $user = User::findOrFail($id);
            $resource = new Item($user, new UserTransformer);
            return $this->fractal->createData($resource)->toArray();
        } catch (\Exception $e) {

            return response()->json(['message' => 'user not found!'], 404);
        }
    }

    /**
     * Create new user
     *
     * @param Request $request
     *
     * @return void
     */
    public function create(Request $request)
    {
        //validate request parameters
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'contact_no' => 'required|unique:users',
            'password' => 'required',
        ]);
        $user = User::create($request->all());
        $user->password = Hash::make($request->password);
        $user->save();
        $resource = new Item($user, new UserTransformer);
        return $this->fractal->createData($resource)->toArray();

    }

    /**
     * Update user by id
     *
     * @param int $id id of user
     * @param Request $request
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'email' => 'email|unique:users',
            'contact_no' => 'unique:users',
        ]);
        if (!User::find($id)) {
            return response()->json(['message' => 'user not found!'], 404);
        }
        $user = User::find($id)->update($request->all());
        if ($user) {
            $resource = new Item(User::find($id), new userTransformer);
            return $this->fractal->createData($resource)->toArray();
        }
        return response()->json(['message' => 'Failed to update user!'], 400);
    }

    /**
     * Delete user by id
     *
     * @param int $id id of user
     *
     * @return void
     */
    public function delete($id)
    {
        if (!User::find($id)) {
            return response()->json(['message' => 'User not found!'], 404);
        }
        if (User::find($id)->delete()) {
            return response()->json(['message' => 'User deleted successfully!'], 410);
        }
        return response()->json(['message' => 'Failed to delete user!'], 400);
    }
}
