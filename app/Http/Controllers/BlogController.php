<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Blogcategory;
use App\Models\Blogtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function slug()
    {
        $title='r u working';
        return Blog::create(
            [
                'title'=>$title,
                'post'=>'post of blog',
                'post_excerpt'=>'post excerpt',
                'user_id'=>1,
                'metaDescription'=>'some meta description',
                'slug'=>\Str::slug($title),
            ]
        );
    }
    public function createBlog(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'post' => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required|max:100',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);
        $categories=$request->category_id;
        $tags=$request->tag_id;
        $blogCategories=[];
        $blogTags=[];
        DB::beginTransaction();
        try {
            $blog=Blog::create(
                [
                    'title'=>$request->title,
                    'post'=>$request->post,
                    'post_excerpt'=>$request->post_excerpt,
                    'user_id'=>Auth::user()->id,
                    'metaDescription'=>$request->metaDescription,
                    'slug'=>\Str::slug($request->title)
                ]
            );
            foreach ($categories as $c)
            {
                array_push($blogCategories,['category_id'=>$c,'blog_id'=>$blog->id]);
            }
            foreach ($tags as $t)
            {
                array_push($blogTags,['tag_id'=>$t,'blog_id'=>$blog->id]);
            }
            Blogcategory::insert($blogCategories);
            Blogtag::insert($blogTags);
            DB::commit();
            return 'Done!';
        }catch (\Throwable $throwable)
        {
            DB::rollBack();
            return response()->json([
                'errors'=>'Blog have not been created for traffic reasons!'
            ],401);
            return 'Not done!';
        }
    }
    public function getBlogs()
    {
        return Blog::with(['tag', 'category'])->orderByDesc('id')->get();
    }
    public function deleteBlogs(Request $request)
    {

        $this->validate($request, [
            'id' => 'required',
        ]);
            return Blog::where('id', $request->id)->delete();


    }
    public function getSingleBlog($id)
    {
        return Blog::with(['tag', 'category'])->where('id',$id)->first();
    }
    public function updateBlog(Request $request,$id)
    {
        $this->validate($request, [
            'title' => 'required',
            'post' => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);
        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = [];
        $blogTags = [];

        DB::beginTransaction();
        try {
             Blog::where('id', $id)->update([
                'title' => $request->title,
                'slug' => $request->title,
                'post' => $request->post,
                'post_excerpt' => $request->post_excerpt,
                'user_id' => Auth::user()->id,
                'metaDescription' => $request->metaDescription
            ]);


            // insert blog categories
            foreach ($categories as $c) {
                array_push($blogCategories, ['category_id' => $c, 'blog_id' => $id]);
            }
            // delete all previous categories
            Blogcategory::where('blog_id', $id)->delete();
            Blogcategory::insert($blogCategories);
            // insert blog tags
            foreach ($tags as $t) {
                array_push($blogTags, ['tag_id' => $t, 'blog_id' => $id]);
            }
            Blogtag::where('blog_id', $id)->delete();
            Blogtag::insert($blogTags);
            DB::commit();
            return 'done';
        } catch (\Throwable $e) {

            DB::rollback();
            return 'not done';
        }
    }
}
