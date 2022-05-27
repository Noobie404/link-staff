<?php
namespace App\Http\Controllers\Api;

use JWTAuth;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use App\Models\UserFollower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->getAuth(); // to get user info fro mjwt token
    }

    public function getAuth(){
        try {
            $this->user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid'],401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token is Expired'],401);
            }else{
                return response()->json(['status' => 'Authorization Token not found'],401);
            }
        }
    }

    public function followPerson(Request $request,$personId)
    {
        DB::beginTransaction();
        try {
            $user_info          = User::find($personId);
            if(empty($user_info)){ // check if user id exists
                return response()->json([
                    'status' => 0,
                    'msg' => 'user not found !'
                ], 200);
            }
            if ($user_info->id == $this->user->id) { //user can not follow themselve
                return response()->json([
                    'status' => 0,
                    'msg' => 'You can not follow yourself !'
                ], 200);
            }
            $check_duplicate_follow = UserFollower::where('user_id',$this->user->id)->where('following_user_id',$personId); // check duplicate follow
            if ($check_duplicate_follow->exists()) {
                return response()->json([
                    'status' => 0,
                    'msg' => 'You are already following this person !'
                ], 200);
            }
            $user_follower                   =  new UserFollower();
            $user_follower->user_id          = $this->user->id;
            $user_follower->following_user_id = $personId;
            $user_follower->save();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return response()->json([
                'status' => 0,
                'msg' => 'Something went wrong !'
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status' => 1,
            'msg' => 'you are now following this person !'
        ], 200);
    }

    public function followPage(Request $request,$pageId)
    {
        DB::beginTransaction();
        try {
            $page_info          = Page::find($pageId);
            if(empty($page_info)){ // check if page id exists
                return response()->json([
                    'status' => 0,
                    'msg' => 'page not found !'
                ], 200);
            }
            $check_duplicate_follow = UserFollower::where('user_id',$this->user->id)->where('following_page_id',$pageId); // check duplicate follow
            if ($check_duplicate_follow->exists()) {
                return response()->json([
                    'status' => 0,
                    'msg' => 'You are already following this page !'
                ], 200);
            }
            $user_follower                   =  new UserFollower();
            $user_follower->user_id          = $this->user->id;
            $user_follower->following_page_id = $pageId;
            $user_follower->save();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 0,
                'msg' => 'Something went wrong !'
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status' => 1,
            'msg' => 'you are now following this page !'
        ], 200);
    }

    public function feed(Request $request)
    {
        DB::beginTransaction();
        try {
            $get_all_folloings = UserFollower::select(DB::raw('group_concat(distinct following_user_id) as user_ids'),DB::raw('group_concat(distinct following_page_id) as page_ids'))->where('user_id',$this->user->id)->groupBy('user_id')->get(); // get both user and page followings

            if ($get_all_folloings->count() > 0) {
                $user_ids = explode(',',$get_all_folloings[0]->user_ids);
                $page_ids = explode(',',$get_all_folloings[0]->page_ids);
            }else{
                return response()->json([
                    'status' => 0,
                    'msg' => 'No news feed available for now !'
                ], 200);
            }
            $feed = Post::select('id','post_content')->whereIn('user_id',$user_ids)->orWhereIn('page_id',$page_ids)->distinct()->get(); // get posts of following users and pages

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 0,
                'msg' => 'Something went wrong !'
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status' => 1,
            'feed' => $feed
        ], 200);
    }
}
