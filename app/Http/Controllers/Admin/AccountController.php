<?php

namespace App\Http\Controllers\Admin;
use DB;
use Illuminate\Routing\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Hash;
class AccountController extends Controller
{
  public function index(Request $request){
    $users = DB::table('users')->select('id','name', 'email','photo', 'phone_number', 'address')->get();
    return view('backend.account.index')->with([
      'users' => $users
    ]);
  }

  public function deleteUser(Request $request){
    DB::table('users')->where('id', $request->id)->delete();
  }
  public function updateUser(Request $request){
    $users = User::all();
    $id = $request->id;
    $name = $request->name;
    $email = $request->email;
    $password = $request->password;
    $phone_number = $request->phone_number;
    $address = $request->address;
    if($request->hasFile("image")){
      $file = $request->file("image");
      $imageName=time().'_'.$file->getClientOriginalName();
      $file->move('uploads/accountImage/',$imageName);
    }
    if($id > 0){
      $users = DB::table('users')->where('id', $id)
      ->update([
        'name' => $name,
        'email' => $email,
        'password'=>bcrypt($request->password),
        'phone_number' => $phone_number,
        'address' => $address,
        'image' => $imageName,
      ]);
    }
    
    
    return redirect('/admin/account');
  }

  public function editUser(Request $request){
    $id = 0;
    $name = $photo = $email = $password = $phone_number = $address = '';
    if(isset($request->id) && $request->id > 0){
      $id = $request->id;
      $std = DB::table('users')
          ->where('id', $id)
          ->get();
          if($std != null && count($std) > 0){
              $name = $std[0]->name;
              $photo = $std[0]->photo;
              $email = $std[0]->email;
              $password = $std[0]->password;
              $phone_number = $std[0]->phone_number;
              $address = $std[0]->address;
          }
    }
    return view('backend.account.edit-user')->with([
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'phone_number' => $phone_number,
        'address' => $address,
        'photo' => $photo,
    ]);
  }

  public function create(Request $request){
    return view('backend.account.add-user');
  }

  public function store(Request $request){
    if(!Auth::check())
    {
      if($request->hasFile("image")){
        $file = $request->file("image");
        $imageName=time().'_'.$file->getClientOriginalName();
        $file->move('uploads/accountImage/',$imageName);
        $user = new User([
          "name" => $request->name,
          "email" => $request->email,
          "password" => Hash::make($request->password),
          "phone_number" => $request->phone_number,
          "address" => $request->address,
          "photo" => $imageName,
        ]);
        $user->save();
      }
      return redirect()->route('account.index');
    }
  }
}
