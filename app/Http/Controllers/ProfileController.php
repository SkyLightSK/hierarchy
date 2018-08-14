<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile', array('user' => Auth::user()));

    }

    public function welcome()
    {
        return view('welcome', array('user' => Auth::user()));

    }

    public function update( Request $request )
    {
        if($request->hasFile('avatar'))
        {
            $avatar = $request->file('avatar');
            $filename = time() . '.'.$avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300,300)->save(public_path('/images/'. $filename));

            $user = Auth::user();
            $user->avatar = $filename;
            $user->save();

            return view('profile', array('user' => Auth::user()));
        }
    }
}
