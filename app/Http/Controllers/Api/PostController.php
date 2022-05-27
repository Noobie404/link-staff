<?php
namespace App\Http\Controllers\Api;

use JWTAuth;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class PostController extends Controller
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

    public function attachPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_content' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }
        DB::beginTransaction();
        try {
            $page               =  new Post();
            $page->post_content = $request->post_content;
            $page->user_id      = $this->user->id;
            $page->post_by_page = 0;
            $page->save();
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
            'msg' => 'Post created successfully !'
        ], 200);
    }

    public function attachPostbyPage(Request $request,$pageId)
    {
        $validator = Validator::make($request->all(), [
            'post_content' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }
        DB::beginTransaction();
        try {
            $page_info          = Page::find($pageId);
            if(empty($page_info)){ // check if page id exists
                return response()->json([
                    'status' => 0,
                    'msg' => 'Page not found !'
                ], 200);
            }
            if ($page_info->user_id != $this->user->id) { //page not owned by logged in user
                return response()->json([
                    'status' => 0,
                    'msg' => 'You do not own this page !'
                ], 200);
            }
            $page               =  new Post();
            $page->post_content = $request->post_content;
            $page->user_id      = $this->user->id;
            $page->page_id      = $pageId;
            $page->post_by_page = 1;
            $page->save();
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
            'msg' => 'Post created successfully !'
        ], 200);
    }
}
