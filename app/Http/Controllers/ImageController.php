<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;

class ImageController extends Controller
{
    //
    public function index(Request $request)
    {
        $photos = Photo::select('photos.*','users.name')->where('placeID', $request['placeID'])->join('users', 'users.id', '=', 'photos.userID')->get();
        return $photos;
    }

    public function store(Request $request)
    {
        
        if ($request->hasFile('photo')) {
            $filenamewithextension = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenametostore = $filename . '_' . time() . '.' . $extension;
            $smallthumbnail = $filename . '_small_' . time() . '.' . $extension;
            $request->file('photo')->storeAs('public/photos', $filenametostore);
            $request->file('photo')->storeAs('public/photos/thumbnail', $smallthumbnail);
            $smallthumbnailpath = public_path('storage/photos/thumbnail/' . $smallthumbnail);
            $this->createThumbnail($smallthumbnailpath, 150, 93);
            $photo = Photo::create(array(
                'placeID'=>$request->postID,
                'imgPathSmall'=>$smallthumbnail,
                'imgPath'=>$filenametostore,
                'userID'=>Auth::user()->id
            ));

            log::create(
                array(
                    'userID' => Auth::user()->id,
                    'action' => 'Uploaded a new Photo [photoID:'. $photo->id . '] in place [placeID:' . $photo->placeID . ']'
                )
            );

            return "success";
        }
    }

    public function createThumbnail($path, $width, $height)
    {
        $img = Image::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
    }

    public function destroy($id)
    {
        if (Photo::where('id', $id)->where('userID', Auth::user()->id)->exists()) {
            $photo = Photo::find($id);
            log::create(
                array(
                    'userID' => Auth::user()->id,
                    'action' => 'Deleted a Photo [photoID:'. $id . '] in place [placeID:' . $photo->placeID . ']'
                )
            );
            Photo::where('id', $id)->where('userID', Auth::user()->id)->delete();
            return 1;
        } else {
            return 401;
        }
    }
}