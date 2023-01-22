<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        if (Like::where('placeID', $id)->where('userID', Auth::user()->id)->exists()) {
            return "alreadyLiked";
        } else {
            $like = Like::create(
                array(
                    'placeID' => $id,
                    'userID' => Auth::user()->id,
                )
            );
            $place = Place::find($id);
            $place->likes += 1;
            $place->save();
            return $place->likes;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Like::where('placeID', $id)->where('userID', Auth::user()->id)->exists()) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Like::where('placeID', $id)->where('userID', Auth::user()->id)->exists()) {
            Like::where('placeID', $id)->where('userID', Auth::user()->id)->delete();
            $place = Place::find($id);
            $place->likes -= 1;
            $place->save();
            return $place->likes;
        } else {
            return 0;
        }
    }
}
