<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PostsRequest;
use App\Models\User;
use App\Models\Posts;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Image;
use File;
use Str;

class PostsController extends Controller
{   

    protected $post_image_path;
    protected $post_image_relative_path;

    /**
     * Create a postcontroller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $this->post_image_path = public_path('uploads/post');
        $this->post_image_relative_path = '/uploads/post';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Posts::latest()->where('status', 'active')->paginate(10);

        $response = [
            'success' => true,
            'data'    => $posts,
            'message' => 'Product list',
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostsRequest $request)
    {
            $input = $request->all();

            //Check isUsrs role is admin or not
            if (Auth::user()->role !='admin') {
                return response()->json(['error'=>'You are not authorized'], 401);
            }

            //Upload image
            $post_image = $request->file('thumbnail');
            if(!empty($post_image))
            {
                $image_info = getimagesize($post_image);

                if($post_image) {
                    $post_image_title = str_replace(' ', '-', time() . '.' . $post_image->getClientOriginalExtension());
                    $post_image_link = $this->post_image_relative_path.'/'.$post_image_title;
                }else{
                    $post_image_link = '';
                    $post_image_title = '';
                }
                $input['thumbnail'] = $post_image_title;
            }
            /* Transaction Start Here */
            DB::beginTransaction();
            try {
                if ($post = Posts::create($input)) {
                   
                   // Store post image
                    if($post_image != null){
                        $post_image->move($this->post_image_path, $post_image_title);
                    }

                    $response['message'] = 'Post Created Successfully!!';
                    $response['data'] = $post;
                    DB::commit();
                }

                return response()->json(['success'=>$response], 200);

            } catch (\Exception $e) {
                //If there are any exceptions, rollback the transaction`
                DB::rollback();
                return response()->json(['error'=>$e->getMessage()], 401);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Posts::findOrFail($id);

        return response()->json(['success'=>$post], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostsRequest $request, $id)
    {
        $input = $request->all();

        //Check isUsrs role is admin or not
        if (Auth::user()->role !='admin') {
            return response()->json(['error'=>'You are not authorized'], 401);
        }


        $model = Posts::where('id',$id)->first();
        if($model)
        {
            $post_image = $request->file('thumbnail');
            if(!empty($post_image)) {
                $post_image_title = str_replace(' ', '-', time() . '.' . $post_image->getClientOriginalExtension());
                $post_image_link = $this->post_image_relative_path.'/'.$post_image_title;

                $imagePath = $this->post_image_relative_path.'/'.$model->thumbnail;
                $realImagePath = public_path($imagePath);
                if(file_exists($realImagePath)){
                    unlink($realImagePath);   
                }
            }else{
                $post_image_link = $model->thumbnail;
                $post_image_title = $model->thumbnail;
            }
            $input['thumbnail'] = $post_image_title;
            
            /* Transaction Start Here */
            DB::beginTransaction();
            try {
                if ($post = $model->update($input)) {

                   // update post image
                    if($post_image != null){
                        $post_image->move($this->post_image_path, $post_image_title);
                    }

                    $response['message'] = 'Post Updated Successfully!!';
                    $response['data'] = $model;
                    DB::commit();
                }

                return response()->json(['success'=>$response], 200);

            } catch (\Exception $e) {
                //If there are any exceptions, rollback the transaction`
                DB::rollback();
                return response()->json(['error'=>$e->getMessage()], 401);
            }

        }else{
            
            return response()->json(['error'=>'This posts not found!'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Check isUsrs role is admin or not
        if (Auth::user()->role !='admin') {
            return response()->json(['error'=>'You are not authorized'], 401);
        }


        $post = Posts::findOrFail($id);
        //image path
        $imagePath = $this->post_image_relative_path.'/'.$post->thumbnail;

        //unlink file from public/upload/post
        if(file_exists(public_path($imagePath))){
            unlink(public_path($imagePath));   
        }
        //deleted posts
        $post->delete();

        $response = [
            'success' => true,
            'message' => 'Post has been Deleted',
        ];

        return response()->json($response, 200);
    }
}
