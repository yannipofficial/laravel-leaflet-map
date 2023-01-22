<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PlaceController extends Controller
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
    public function store(Request $request)
    {
        $place = Place::create(array(
            'name' => $request['name'],
            'cat' => $request['category'],
            'description' => $request['description'],
            'userID' => Auth::user()->id,
            'lat' => $request['latitude'],
            'long' => $request['longitude']
        ));

        log::create(
            array(
                'userID' => Auth::user()->id,
                'action' => 'Created a new place [placeID:'. $place->id . ']'
            )
        );
        
        return $place->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Place::with('user:id,name')->find($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function likes($id)
    {
        return Place::find($id)->likes()->count();
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
        $place = Place::find($id);

        if ($place->userID === Auth::user()->id || Auth::user()->isAdmin()) {
            $place->name = $request['name'];
            $place->cat = $request['category'];
            $place->description = $request['description'];

            $place->save();

            log::create(
                array(
                    'userID' => Auth::user()->id,
                    'action' => 'Updated a place [placeID:'. $place->id . ']'
                )
            );

            return $place;
        } else {
            return 401;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $place = Place::find($id);
        if ($place->userID == Auth::user()->id || Auth::user()->isAdmin()) {
            log::create(
                array(
                    'userID' => Auth::user()->id,
                    'action' => 'Deleted a place [placeID:'. $id . ']'
                )
            );
            $place->delete();
        } else {
            return 401;
        }
    }
}
