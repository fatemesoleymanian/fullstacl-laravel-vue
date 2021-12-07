<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // first check if you are loggedin and admin user ...

        if (!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }
        if (!Auth::check() && $request->path() == 'login') {

            return view('welcome');
        }
        // you are already logged in... so check for if you are an admin user..
        $user = Auth::user();

        if ($user->userType == 'User') {
            return redirect('/login');
        }
        if ($request->path() == 'login') {
            return redirect('/');
        }


        return view('welcome');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function addTag(Request $request)
    {
        // validate request
        $this->validate($request, [
            'tagName' => 'required',
        ]);

        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }
    public function getTag()
    {
            return Tag::orderByDesc('id')->get();
    }
    public function editTag(Request $request)
    {
        // validate request
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required',
        ]);
        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName,
        ]);
    }
    public function deleteTag(Request $request)
    {
        // validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Tag::where('id', $request->id)->delete();
    }
    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png|image',
        ]);
//        dd($request->file->getClientOriginalName());
        $picName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $picName);
        return $picName;
    }
        public function deleteImage(Request $request)
        {
           $this->deleteFromserver($request->imageName,false);
            return 'done!';


        }
        public function deleteFromserver($fileName, $hasFullPath=false)
        {
            if(!$hasFullPath)
            {
                $file_path = public_path().$fileName;
            }

            if (file_exists($file_path))
            {
                @unlink($file_path);
            }

        }
    public function addCategory(Request $request)
    {
        // validate request
        $this->validate($request, [
            'categoryName' => 'required',
            'IconImage' => 'required'
        ]);

        return Category::create([
            'categoryName' => $request->categoryName,
            'IconImage' => $request->IconImage
        ]);
    }
    public function getCategory()
    {
        return Category::orderByDesc('id')->get();
    }
    public function editCategory(Request $request)
    {
        // validate request
        $this->validate($request, [
            'categoryName' => 'required',
            'IconImage' => 'required',
        ]);
        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'IconImage' => $request->IconImage
        ]);
    }
    public function deleteCategory(Request $request)
    {
        $this->deleteFromserver($request->IconImage);
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Category::where('id', $request->id)->delete();
    }
    public function addAdmin(Request $request)
    {
        $this->validate($request, [
            'fullName' => 'required',
            'password' => 'bail|required|min:6',
            'userType' => 'required',
            'email' => 'bail|required|email|unique:users',
        ]);
         $password = bcrypt($request->password);
         $user = User::create([
             'fullName' => $request->fullName,
             'password' => $password,
             'userType' => $request->userType,
             'email' => $request->email,
         ]);
         return $user;
    }
    public function getAdmin()
    {
        return User::where('userType','!=','User')->orderByDesc('id')->get();
    }
    public function editAdmin(Request $request)
    {
        $this->validate($request, [
            'fullName' => 'required',
            'password' => 'min:6',
            'userType' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",
        ]);
        $data=[
            'fullName' => $request->fullName,
            'userType' => $request->userType,
            'email' => $request->email,
        ];
        if($request->password) {
            $password = bcrypt($request->password);
            $data['password']=$password;
        }
        $user = User::where('id',$request->id)->update($data);
        return $user;
    }
    public function deleteAdmin(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        return User::where('id', $request->id)->delete();
    }
    public function adminLogin(Request $request)
    {
        $this->validate($request,[
            'email'=>'bail|required|email',
            'password'=>'bail|required|min:6'
        ]);
        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password,]))
        {
            $user = Auth::user();
            if ($user->userType == 'User')
            {
                Auth::logout();
                return response()->json([
                    'msg'=>'Not allowed to access admin dashboard!!'
                ],401);
            }
            return response()->json([
                'msg'=>'You are logged in successfully!',
                'user'=>$user
            ]);
        }else{
            return response()->json([
                'msg'=>'Incorrect login details!'
            ],401);
        }
    }
    //***********************************Role
    public function addRole(Request $request)
    {
        $this->validate($request,[
            'roleName'=>'required'
        ]);
        return Role::create([
            'roleName'=>$request->roleName
        ]);
    }
    public function editRole(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required'
        ]);
        return Role::where('id', $request->id)->update([
            'roleName' => $request->roleName,
        ]);
    }
    public function deleteRole(Request $request)
    {

        $this->validate($request, [
            'id' => 'required',
        ]);
        return Role::where('id', $request->id)->delete();
    }
    public function getRoles()
    {
        return Role::all();
    }

}
