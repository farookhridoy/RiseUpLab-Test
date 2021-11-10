<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class AuthenticatedController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'role' => 'required',
        ]);
        
        //If validation fails then retrun error message with code 401
        if($validator->fails()){
        	 return response()->json(['error'=>$validator->errors()], 401);     
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        // Transaction Start Here
        DB::beginTransaction();
        try{
            $user = User::create($input);
            if($user) {
               $response['name'] =  $user->name;
               $response['message'] = 'Registration Successfull';
               //Commit Data
               DB::commit();
            }
           return response()->json(['success'=>$response], 200);
        }catch (\Exception $e ){

            //If there are any exceptions, rollback the transaction
            DB::rollback();
            return response()->json(['error'=>$e->getMessage()], 401);
        }
    }

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 

    public function login(Request $request){ 

    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
        	 return response()->json(['error'=>$validator->errors()], 401);     
        }

    	if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
    		$user = Auth::user(); 
    		$success['token'] =  $user->createToken('RiseUpLabsApps')->accessToken; 
    		$success['userId'] = $user->id;
            $success['message'] = 'Successfully logged in as a '.$user->role. '!!';
    		return response()->json(['success' => $success], 200); 
    	} 
    	else{ 
    		return response()->json(['error'=>'Unauthorised'], 401); 
    	} 
    }

    /** 
     * logout api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
 
}
