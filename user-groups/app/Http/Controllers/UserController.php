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

    protected function getDateFormat()
    {
        return 'U';
    }

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

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'email' => 'email|unique:users',
            'contact_no' => 'unique:users',
        ]);
        //Return error 404 response if user was not found
        if (!User::find($id)) {
            return response()->json(['message' => 'user not found!'], 404);
        }
        $user = User::find($id)->update($request->all());
        if ($user) {
            //return updated data
            $resource = new Item(User::find($id), new userTransformer);
            return $this->fractal->createData($resource)->toArray();
        }
        //Return error 400 response if updated was not successful
        return response()->json(['message' => 'Failed to update user!'], 400);
    }

    public function delete($id)
    {
        //Return error 404 response if user was not found
        if (!User::find($id)) {
            return response()->json(['message' => 'User not found!'], 404);
        }
        //Return  success response if delete was successful
        if (User::find($id)->delete()) {
            return response()->json(['message' => 'User deleted successfully!'], 410);
        }
        //Return error response if delete was not successful
        return response()->json(['message' => 'Failed to delete user!'], 400);
    }
}
