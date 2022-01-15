<?php

namespace App\Http\Controllers;

use App\Models\Replies;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function getReply ($id)
    {
        return Replies::with(['user'])->where('comment_id',$id)->orderByDesc('id')->get();

    }
    public function changePublish(Request $request,$id)
    {
        return Replies::where('id',$id)->update([
            'isPublished'=>$request->pub
        ]);
    }
    public function deleteReply(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Replies::where('id',$request->id)->delete();
    }
}
