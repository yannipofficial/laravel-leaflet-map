<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function store($id, Request $request)
    {
        $comment = Comment::create(
            array(
                'userID' => Auth::user()->id,
                'placeID' => $id,
                'commentBody' => $request['commentBody']
            )
        );

        log::create(
            array(
                'userID' => Auth::user()->id,
                'action' => 'Created a new comment [commentID:'. $comment->id . '] in place [placeID:' . $comment->placeID . ']'
            )
        );

        return $comment->placeID;
    }

    public function destroy($commentId)
    {
        $comment = Comment::find($commentId);
        if ((($comment->userID) == (Auth::user()->id)) || (Auth::user()->isAdmin() == "true")) {
            $comment->delete();
            
            log::create(
                array(
                    'userID' => Auth::user()->id,
                    'action' => 'Deleted a comment [commentID:'. $comment->id . '] in place [placeID:' . $comment->placeID . ']'
                )
            );

            return 1;
        } else {
            return 401;
        }

    }

    public function show($id)
    { 
        return Place::find($id)->comments()->with('user:id,name')->orderBy('created_at', 'DESC')->paginate(5);
    }
}