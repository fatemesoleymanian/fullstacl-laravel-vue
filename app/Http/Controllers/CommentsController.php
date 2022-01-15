<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function getComment ($id)
    {
        return Comments::with(['user'])->where('blog_id',$id)->orderByDesc('id')->get();

    }
    public function changePublish(Request $request,$id)
    {
        return Comments::where('id',$id)->update([
            'isPublished'=>$request->pub
        ]);
    }
    public function deleteComment(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Comments::where('id',$request->id)->delete();
    }
}
