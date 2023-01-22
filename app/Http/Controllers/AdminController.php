<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Log;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.dash');
    }
    public function places()
    {
        return view('admin.places');
    }
    public function users()
    {
        return view('admin.users');
    }
    public function comments()
    {
        return view('admin.comments');
    }
    public function categories()
    {
        return view('admin.categories');
    }
    public function logs()
    {
        return view('admin.logs');
    }



    public function orderPlaces(Request $request)
    {
        if ($request['search'] != null) {

            $places = Place::orWhere('id', 'like', $request['search'])
                ->orWhere('cat', 'like', '%' . $request['search'] . '%')
                ->orWhere('name', 'like', '%' . $request['search'] . '%')
                ->orWhere('description', 'like', '%' . $request['search'] . '%')
                ->orWhere('lat', 'like', '%' . $request['search'] . '%')
                ->orWhere('long', 'like', '%' . $request['search'] . '%')
                ->paginate($request['limit']);
        } else {
            $places = Place::paginate($request['limit']);
        }

        $places = $places->toArray();
        $place['total'] = $places['total'];
        $place['totalNotFiltered'] = $places['total'];
        $place['rows'] = $places['data'];

        return response()->json($place);
    }

    public function updatePlaces($id, Request $request)
    {

        $place = Place::updateOrCreate(
            ['id' => $id],
            ['userID' => $request['userid'], 'long' => $request['longitude'], 'lat' => $request['latitude'], 'name' => $request['name'], 'cat' => $request['category'], 'description' => $request['description']]
        );
        $place->save();
        return $place;
    }

    public function deletePlace($id)
    {
        Place::find($id)->delete();
    }

    public function orderUsers(Request $request)
    {
        if ($request['search'] != null) {
            $users = User::orWhere('id', 'like', $request['search'])
                ->orWhere('name', 'like', '%' . $request['search'] . '%')
                ->orWhere('email', 'like', '%' . $request['search'] . '%')
                ->orWhere('created_at', 'like', '%' . $request['search'] . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($request['limit'])->orderBy('created_at', 'desc');
        } else {
            $users = User::orderBy('created_at', 'desc')->paginate($request['limit']);
        }

        $users = $users->toArray();
        $user['total'] = $users['total'];
        $user['totalNotFiltered'] = $users['total'];
        $user['rows'] = $users['data'];

        return response()->json($user);
    }

    public function updateUsers($id, Request $request)
    {
        if ($request['password'] !== null) {
            $request['password'] = Hash::make($request['password']);
            $user = User::updateOrCreate(
                ['id' => $id],
                ['name' => $request['name'], 'email' => $request['email'], 'password' => $request['password'], 'role' => $request['role']]
            );
        } else {
            $user = User::updateOrCreate(
                ['id' => $id],
                ['name' => $request['name'], 'email' => $request['email'], 'role' => $request['role']]
            );
        }

        $user->save();
        return $user;
    }

    public function deleteUser($id)
    {
        User::find($id)->delete();
    }

    public function orderComments(Request $request)
    {
        if ($request['search'] != null) {
            $comments = Comment::select('comments.*', 'users.name')
                ->orWhere('comments.id', 'like', $request['search'])
                ->orWhere('userID', 'like', '%' . $request['search'] . '%')
                ->orWhere('users.name', 'like', '%' . $request['search'] . '%')
                ->orWhere('commentBody', 'like', '%' . $request['search'] . '%')
                ->orWhere('placeID', 'like', '%' . $request['search'] . '%')
                ->orWhere('comments.created_at', 'like', '%' . $request['search'] . '%')
                ->join('users', 'users.id', '=', 'comments.userID')
                ->orderBy('created_at', 'desc')
                ->paginate($request['limit']);
        } else {
            $comments = Comment::select('comments.*', 'users.name')->join('users', 'users.id', '=', 'comments.userID')->orderBy('created_at', 'desc')->paginate($request['limit']);
        }

        $comments = $comments->toArray();
        $comment['total'] = $comments['total'];
        $comment['totalNotFiltered'] = $comments['total'];
        $comment['rows'] = $comments['data'];

        return response()->json($comment);
    }

    public function updateComments($id, Request $request)
    {

        $comment = Comment::updateOrCreate(
            ['id' => $id],
            ['userID' => $request['userid'], 'placeID' => $request['placeid'], 'commentBody' => $request['comment']]
        );
        $comment->save();
        return $comment;
    }

    public function deleteComment($id)
    {
        Comment::find($id)->delete();
    }

    public function orderCategories(Request $request)
    {
        if ($request['search'] != null) {
            $categories = Category::orWhere('id', 'like', $request['search'])
                ->orWhere('name', 'like', '%' . $request['search'] . '%')
                ->orWhere('color', 'like', '%' . $request['search'] . '%')
                ->orWhere('created_at', 'like', '%' . $request['search'] . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($request['limit']);
        } else {
            $categories = Category::orderBy('created_at', 'desc')->paginate($request['limit']);
        }

        $categories = $categories->toArray();
        $category['total'] = $categories['total'];
        $category['totalNotFiltered'] = $categories['total'];
        $category['rows'] = $categories['data'];

        return response()->json($category);
    }
    public function updateCategories($id, Request $request)
    {

        $cat = Category::updateOrCreate(
            ['id' => $id],
            ['name' => $request['name'], 'color' => $request['color']]
        );
        $cat->save();
        return $cat;
    }

    public function deleteCategory($id)
    {
        Category::find($id)->delete();
    }

    public function orderLogs(Request $request)
    {
        if ($request['search'] != null) {
            $logs = Log::orWhere('id', 'like', $request['search'])
                ->orWhere('userID', 'like', '%' . $request['search'] . '%')
                ->orWhere('action', 'like', '%' . $request['search'] . '%')
                ->orWhere('created_at', 'like', '%' . $request['search'] . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($request['limit']);
        } else {
            $logs = Log::orderBy('created_at', 'desc')->paginate($request['limit']);
        }

        $logs = $logs->toArray();
        $log['total'] = $logs['total'];
        $log['totalNotFiltered'] = $logs['total'];
        $log['rows'] = $logs['data'];

        return response()->json($log);
    }
}
