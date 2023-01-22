<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Place;
use App\Models\Category;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
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
        $places = Place::all();
        $categories = Category::all();
        return view('home')
            ->with(['places' => $places])
            ->with(['categories' => $categories]);
    }

    public function indexPlace($id)
    {
        $places = Place::all();
        $categories = Category::all();
        return view('home')
            ->with('id', $id)
            ->with(['categories' => $categories])
            ->with(['places' => $places]);
    }


    public function orderSearch(Request $request)
    {
        if ($request['search'] != null) {
            $results = Place::orWhere('id', 'like', $request['search'])
                ->orWhere('cat', 'like', '%' . $request['search'] . '%')
                ->orWhere('name', 'like', '%' . $request['search'] . '%')
                ->orWhere('description', 'like', '%' . $request['search'] . '%')
                ->orWhere('lat', 'like', '%' . $request['search'] . '%')
                ->orWhere('long', 'like', '%' . $request['search'] . '%')
                ->orWhere('created_at', 'like', '%' . $request['search'] . '%')
                ->orderBy('likes', 'DESC')
                ->paginate(9);


            $results = $results->toArray();

            $result[0] = $results['total'];
            $result[1] = $results['last_page'];
            $result[2] = $results['current_page'];

            return [$results['data'], $result];
        }
    }
}
