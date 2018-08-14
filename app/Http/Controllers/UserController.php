<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
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

        $users = \App\User::with('userable')->paginate(25);
        return view('usersList', compact( 'users'));

    }

    /**
     *
     * Response for search
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function search( Request $request )
    {

        $keyword = $request->keyword;


        $users = \App\User::with('userable')
            ->where('name', $keyword)
            ->orwhere('position', $keyword)
            ->orwhere('recruitment_date', $keyword)
            ->orwhere('salary_size', $keyword)
            ->orwhere('ruler_name', $keyword)
            ->paginate(25, ['*'], 'page', 1);

        $response = view("layouts.searchUsers")->with('users', $users)->render();
        return response()->json(array('msg'=> $response), 200);
    }

    /**
     * Response for search pagination
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function searchPagination( Request $request )
    {

        $keyword = $request->keyword;
        $page    = $request->page;

        $users = \App\User::with('userable')
            ->where('name', $keyword)
            ->orwhere('position', $keyword)
            ->orwhere('recruitment_date', $keyword)
            ->orwhere('salary_size', $keyword)
            ->orwhere('ruler_name', $keyword)
            ->paginate(25, ['*'], 'page', $page);

        $response = view("layouts.searchUsers")->with('users', $users)->render();
        return response()->json(array('msg'=> $response), 200);
    }
}
