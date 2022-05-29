<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Subscription;
use Validator;
class SubscriptionsController extends Controller
{
    public function create_post(Request $request){
        $rules = [
            'title' => 'bail|required|string|max:255',
            'description' => 'bail|required|string',
            'body' => 'bail|required|string',
            'web_id' => 'bail|required|integer',
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails())
            return response()->json(['errors'=>$validation->errors()]);

        if($validation){
            $postAlreadyExist = Post::where([
                ['title',trim($request->title)], //it can be slug depeinng upon requirement
                ['web_id',trim($request->web_id)]
            ])->first();
            if($postAlreadyExist){
                $response = ['status'=>'error','msg'=>'Sorry post with same title already Exist'];
                return response()->json($response,210);
            }
            $post = Post::create([
                'title'=> trim($request->title),
                'description'=> trim($request->description),
                'body'=> trim($request->body),
                'web_id'=> trim($request->web_id),
            ]);

            if($post){
                // dispatch job to this website Subscribers
                $data = [
                    'title'=> trim($request->title),
                    'description'=> trim($request->description),
                    'web_id'=> trim($request->web_id),
                ];

                $NotifySubscribers = (new \App\Jobs\SendEmail($data))
                ->delay(now()->addSeconds(1));

                dispatch($NotifySubscribers);

                // ##### OR WE CAN USE COMMAND LIKE THIS TO DISPATCH JOB but i'm not much familiar with this method ####
                // \Artisan::call('emails:notify:usersAboutPost', ['data' => $data]);

                $response = ['status'=>'success','msg'=>'Post Added'];
                return response()->json($response,200);
            }else{
                $response = ['status'=>'error','msg'=>'Something went wrong, Could not add your post!'];
                return response()->json($response,211);
            }
        }
    }


    public function subcribe_to_website(Request $request){
        $rules = [
            'web_id' => 'bail|required|integer',
            'u_id' => 'bail|required|integer',
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails())
            return response()->json(['errors'=>$validation->errors()]);

        if($validation){

            $SubAlreadyExist = Subscription::where([
                ['u_id',trim($request->title)],
                ['web_id',trim($request->web_id)]
            ])->first();
            if($SubAlreadyExist){
                $response = ['status'=>'error','msg'=>'You Already Subscribed to this website.'];
                return response()->json($response,210);
            }
            $Subscribed = Subscription::create([
                'u_id'=> intval(trim($request->u_id)), // it can unique strings but depends how we used in our db in my case i used integers as primary key
                'web_id'=> intval(trim($request->web_id)), // it can unique strings but depends how we used in our db in my case i used integers as primary key
            ]);

            if($Subscribed){
                $response = ['status'=>'success','msg'=>'Successfuly Subscribed, Now you will be notified about this site posts'];
                return response()->json($response,200);
            }else{
                $response = ['status'=>'error','msg'=>'Something went wrong, Could not Subscribed!'];
                return response()->json($response,211);
            }
        }
    }
}
