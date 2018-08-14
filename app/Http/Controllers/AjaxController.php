<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;


class AjaxController extends Controller
{
    /**
     *
     * Response to table changes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index( Request $request ){

        $user_id        = $request->user_id;
        $user_type      = $request->user_type;
        $user_post      = $request->user_post;
        $position_id    = $request->position_id;
        $field          = $request->field;
        $new_data       = strip_tags($request->new_data);


        if ($user_type == 'user')
        {

            if ($field == 'position'){

                $result = self::change_position($new_data, $user_post, $position_id, $user_id, $field );

            }else{

                User::with('userable')->where('id', $user_id)->update([$field => $new_data]);

                $result = true;
            }

        }else{

            switch ($user_post):
                case 'ceo':

                    \App\CEO::with('user')->where('id', $position_id)->update([$field => $new_data]);
                    User::with('userable')->where('id', $user_id)->update([$field => $new_data]);

                    $result = true;

                    break;
                case 'director':

                    if ($field == 'ruler_name'){

                        $result = self::change_ruler($position_id, $user_post, $new_data, $user_id, $field);

                        }else {

                        \App\Director::with('user')->where('id', $position_id)->update([$field => $new_data]);
                        User::with('userable')->where('id', $user_id)->update([$field => $new_data]);

                        $result = true;

                    }
                    break;
                case 'manager':

                    if ($field == 'ruler_name'){

                      $result = self::change_ruler($position_id, $user_post, $new_data, $user_id, $field);

                      }else {

                        \App\Manager::with('user')->where('id', $position_id)->update([$field => $new_data]);
                        User::with('userable')->where('id', $user_id)->update([$field => $new_data]);

                        $result = true;

                    }
                    break;
                case 'team_lead':
                    if ($field == 'ruler_name'){

                        $result = self::change_ruler($position_id, $user_post, $new_data, $user_id, $field);

                        }else {

                        \App\Team_Lead::with('user')->where('id', $position_id)->update([$field => $new_data]);
                        User::with('userable')->where('id', $user_id)->update([$field => $new_data]);

                        $result = true;

                    }
                    break;
                case 'staff':
                    if ($field == 'ruler_name'){

                        $result = self::change_ruler($position_id, $user_post, $new_data, $user_id, $field);

                        }else {

                        \App\Staff::with('user')->where('id', $position_id)->update([$field => $new_data]);
                        User::with('userable')->where('id', $user_id)->update([$field => $new_data]);

                        $result = true;

                    }
                    break;
                default:
                    $msg = "Position like dat don`t exists";
                    return response()->json(array('msg'=> $msg), 200);
            endswitch;
        }

        $msg = $result;
        return response()->json(array('msg'=> $msg), 200);
    }

    /**
     * Changing user position
     *
     * @param $new_data
     * @param $user_post
     * @param $position_id
     * @param $user_id
     * @param $field
     *
     * @return bool
     */
    public static function change_position($new_data, $user_post, $position_id, $user_id, $field ){

        if($user_post === $new_data) return true;

        $position = self::position_exists($new_data);

        if ($position){

            $post_class = self::post_class($user_post);
            $user_position = $post_class::with('subordinates')->where('id',$position_id)->get();
            $subordinates = $user_position[0]['subordinates'];

            if( isset($subordinates) ){

                $subordinates_class = self::subordinates_class($user_post);
                $position_db_format = self::position_db_format($user_post);
                $first_position_name = $post_class::with('user')->where('id', 1)->get();

                $new_position_class = self::post_class($new_data);

                $subordinates_class::where($position_db_format .'_id', $position_id)->update(
                    [
                        $position_db_format .'_id' => 1,
                        'ruler_name' => $first_position_name[0]['user']['name']
                    ]);

                $new_position = $new_position_class::create();

                User::with('userable')->where('id', $user_id)
                    ->update(
                    [
                        'userable_id' => $new_position->id,
                        'userable_type' => $new_position_class,
                        $field => $new_data,
                    ]);

                return true;

            }else{

                return true;
            }

        }else{

            return false;

        }
    }

    /**
     * Change ruler
     *
     * @param $position_id
     * @param $user_post
     * @param $new_data
     * @param $user_id
     * @param $field
     *
     * @return bool
     */
    public static function change_ruler( $position_id , $user_post , $new_data , $user_id, $field ){

        $ruler_post = self::ruler_post($user_post);
        $ruler_post_db = self::ruler_post_db($user_post);
        $position_class = self::post_class($user_post);

        $ruler = \App\User::with('userable')->where('position', $ruler_post )->where('name', $new_data)->get();

        if( isset($ruler[0]['name']) ){

            $position_class::where('id', $position_id )
                ->update([$ruler_post_db . '_id' => $ruler[0]['userable']['id'] ,
                        'ruler_name' => $new_data]);

            User::with('userable')->where('id', $user_id)->update([$field => $new_data]);


            $new_data = true ;

        }else{

            $new_data = false;
        }

        return $new_data;

    }

    /**
     * User position class
     *
     * @param $user_post
     *
     * @return class
     */
    public static function post_class($user_post){
        switch ($user_post):
            case 'ceo':
                return \App\CEO::class;
                break;
            case 'director':
                return \App\Director::class;
                break;
            case 'manager':
                return \App\Manager::class;
                break;
            case 'team_lead':
                return \App\Team_Lead::class;
                break;
            case 'staff':
                return \App\Staff::class;
                break;
            default:
                return "Position like dat don`t exists";
        endswitch;
    }

    /**
     * Subordinates class
     *
     * @param $user_post
     *
     * @return class
     */
    public static function subordinates_class($user_post){
        switch ($user_post):
            case 'ceo':
                return \App\Director::class;
                break;
            case 'director':
                return \App\Manager::class;
                break;
            case 'manager':
                return \App\Team_Lead::class;
                break;
            case 'team_lead':
                return \App\Staff::class;
                break;
            default:
                return "Position like dat don`t exists";
        endswitch;
    }

    /**
     * Ruler post
     *
     * @param $user_post
     * @return string
     */
    public static function ruler_post($user_post){
        switch ($user_post):
            case 'director':
                return 'ceo';
                break;
            case 'manager':
                return 'director';
                break;
            case 'team_lead':
                return 'manager';
                break;
            case 'staff':
                return 'team_lead';
                break;
            default:
                return "Position like dat don`t exists";
        endswitch;
    }

    /**
     * Subordinate position
     *
     * @param $user_post
     * @return string
     *
     */
    public static function subordinate_post($user_post){
        switch ($user_post):
            case 'ceo':
                return 'director';
                break;
            case 'director':
                return 'manager';
                break;
            case 'manager':
                return 'team_lead';
                break;
            case 'team_lead':
                return 'staff';
                break;
            default:
                return "Position like dat don`t exists";
        endswitch;
    }

    /**
     * Position exists
     *
     * @param $user_post
     * @return bool
     */
    public static function position_exists($user_post){
        switch ($user_post):
            case 'ceo':
                return true;
                break;
            case 'director':
                return true;
                break;
            case 'manager':
                return true;
                break;
            case 'team_lead':
                return true;
                break;
            case 'staff':
                return true;
                break;
            default:
                return false;
        endswitch;
    }

    /**
     * Ruler position in data base format
     *
     * @param $user_post
     * @return string
     */
    public static function ruler_post_db($user_post){
        switch ($user_post):
            case 'director':
                return 'c_e_o';
                break;
            case 'manager':
                return 'director';
                break;
            case 'team_lead':
                return 'manager';
                break;
            case 'staff':
                return 'team__lead';
                break;
            default:
                return "Position like dat don`t exists";
        endswitch;
    }

    /**
     * Position database format
     *
     * @param $user_post
     * @return string
     */
    public static function position_db_format($user_post){
        switch ($user_post):
            case 'ceo':
                return 'c_e_o';
                break;
            case 'director':
                return 'director';
                break;
            case 'manager':
                return 'manager';
                break;
            case 'team_lead':
                return 'team__lead';
                break;
            case 'staff':
                return 'staff';
                break;
            default:
                return "Position like dat don`t exists";
        endswitch;
    }

}