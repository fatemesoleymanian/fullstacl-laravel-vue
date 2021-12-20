<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function upload(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|mimes:jpeg,jpg,png|image',
        ]);
//        dd($request->file->getClientOriginalName());
        $picName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads\blog'), $picName);
        return response()->json([
            'success'=> 1,
            'file'=>["url"=>"http://localhost:8000/uploads/blog/$picName"]
        ]);
        return $picName;
    }
}
