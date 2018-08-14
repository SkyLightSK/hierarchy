<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\CEO;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
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
        $ceos = \App\CEO::with('user')->with('subordinates')->get();
        $directors = \App\Director::with('user')->with('subordinates')->get();
        $managers = \App\Manager::with('user')->with('subordinates')->get();
        //$team_leads = \App\Team_Lead::with('user')->with('subordinates')->get();

        $hierarchy = array(
            array(
                'obj' => $ceos
            ),
            array(
                'obj' => $directors,
                'id'  => 'c_e_o_id'
            ),
            array(
                'obj' => $managers,
                'id'  => 'director_id'
            ),
//            array(
//                'obj' => $team_leads,
//                'id'  => 'manager_id'
//            ),

        );

        $users = $this->getSubordinates( $hierarchy , 0 , 0);

        return view('home', compact( 'users'));
    }

    /**
     *
     * Create startup hierarchy
     *
     * @param $parents
     * @param $parent_id
     * @param $n
     * @return array
     */
    public static function getSubordinates( $parents, $parent_id , $n ){

        $res = array();

        if($n == 0){

            $n++;

            foreach ($parents[ $n -1 ]['obj'] as $key => $parent) {

                $arr =  array(
                    'id' => $parent->id,
                    'user' => $parent->user,
                    'name' => $parent->user['name'],
                    'position' => $parent->user['position'],
                    'subordinates' => self::getSubordinates($parents, $parent->id, $n)
                ) ;

                if($arr) {
                    $res[] = $arr;
                }
            }

        }else {

            $n++;

            foreach ($parents[ $n-1 ]['obj'] as $key => $parent) {

                if ($parent[$parents[ $n-1 ]['id']] == $parent_id) {

                    $arr = array(
                        'id' => $parent->id,
                        'user' => $parent->user,
                        'name' => $parent->user['name'],
                        'position' => $parent->user['position'],
                        'subordinates' =>
                            $parents[ $n-1 ]['id'] == $parents[count($parents)-1]['id']
                            ?
                            array()
                            :
                            self::getSubordinates($parents, $parent->id, $n)

                    );

                    if ($arr) {
                        $res[] = $arr;
                    }
                }
            }

        }
        return $res;

    }

    /**
     * Response to unload
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unload( Request $request )
    {
        $position_id =$request->position_id;
        $user_post =$request->user_post;
        $depth = $request->depth;

        $post_class = AjaxController::post_class($user_post);

        $position = $post_class::with('user')->with('subordinates')->where('id', $position_id )->get();
        $childs = $position[0]['subordinates'];

        $msg = view("layouts.manageChild")->with('childs', $childs)->with('depth', $depth+1 )->render();

        return response()->json(array('msg'=> $msg), 200);
    }

    /**
     * Response for updating hierarchy and roles
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateHierarchy( Request $request )
    {
        $place_position         = $request->place_position;
        $child_position_id      = $request->child_position_id;
        $child_id               = $request->child_id;
        $new_parent_id          = $request->new_parent_id;
        $new_parent_position_id = $request->new_parent_position_id;

        $child_position_class = AjaxController::post_class(self::get_position_by_id($child_id));

        if($place_position == 'ceo'){

            $child_user = User::with('userable')->where('id', $child_id)->get();

             $new_ceo = CEO::create();
             $new_ceo->salary_size = $child_user[0]['salary_size'];
             $new_ceo->save();

            User::with('userable')->where('id', $child_id)
                ->update(
                    [
                        'userable_id'   => $new_ceo->id,
                        'userable_type' => CEO::class,
                        'salary_size'   => $child_user[0]['salary_size'],
                        'position'      => CEO::TYPE,
                        'ruler_name'    => ''
                    ]);

            $child_position_class::where('id', $child_position_id)->delete();

            $res = User::with('userable')->where('id', $child_id)->get();
            return response()->json(array('msg'=> $res), 200);
        }

        $new_parent = User::with('userable')->where('id', $new_parent_id)->get();
        $parent_position_db = AjaxController::position_db_format($new_parent[0]['position']);
        $child_position = $child_position_class::where('id', $child_position_id)->get();
        $old_parent_position = AjaxController::ruler_post($child_position[0]['position']);

        if($old_parent_position == $new_parent[0]['position']){

            User::with('userable')->where('id', $child_id)
                ->update([
                    'ruler_name' => $new_parent[0]['name']
                ]);

            $child_position_class::where('id', $child_position_id)
                ->update([
                    'ruler_name'    => $new_parent[0]['name'],
                    $parent_position_db . '_id' => $new_parent_position_id
                ]);

            $msg = ' child id: '.$child_id . ' positon id: ' . $child_position_id. ' and parent user id: ' . $new_parent_id . ' parent positon id: ' . $new_parent_position_id
                . ' other res: ' . $old_parent_position ;

            return response()->json(array('msg'=> $msg), 200);

        }else{

            $new_position = AjaxController::subordinate_post($new_parent[0]['position']);
            $new_child_position_class = AjaxController::post_class($new_position);

            $new_set_position = $new_child_position_class::create();
            $new_set_position->save();
            $new_child_position_class::where('id' , $new_set_position->id)
                ->update([
                    'ruler_name'                => $new_parent[0]['name'],
                    $parent_position_db . '_id' => $new_parent_position_id,
                    'salary_size'               => $child_position[0]['salary_size'],
                ]);

            User::with('userable')->where('id', $child_id)
                ->update(
                    [
                        'userable_id'   => $new_set_position->id,
                        'userable_type' => $new_child_position_class,
                        'position'      => $new_position,
                        'salary_size'   => $child_position[0]['salary_size'],
                        'ruler_name'    => $new_parent[0]['name']
                    ]);

            $child_position_class::where('id', $child_position_id)->delete();

            $res = User::with('userable')->where('id', $child_id)->get();

            return response()->json(array('msg'=> $res), 200);

        }

    }

    /**
     * Return user position corresponding on user id
     *
     * @param $user_id
     * @return mixed
     */
    public static function get_position_by_id( $user_id )
    {
        $user = User::with('userable')->where('id', $user_id)->get();
        $position_class = $user[0]['position'];
        return $position_class;
    }

}
