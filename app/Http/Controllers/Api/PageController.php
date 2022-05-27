<?php
namespace App\Http\Controllers\Api;

use JWTAuth;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class PageController extends Controller
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

    public function storePage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }
        DB::beginTransaction();
        try {
            $page               =  new Page();
            $page->page_name    = $request->page_name;
            $page->user_id      = $this->user->id;
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
            'msg' => 'Page created successfully !'
        ], 200);
    }
}
