<?php


namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;

class PostController extends BaseController
{
    public function index()
    {
        $data['posts'] = Post::all();
        //**  Short Code Enherit baseController class
        return $this->sendResponse($data, 'All Post Data.');

        //**  Large Coding 
        // return response()->json([
        //   'status'=> true,
        //   'message'=> 'All Post Data.',
        //   'data'=> $data
        // ], 200);

    }

    public function store(Request $request)
    {
        $validatePost = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
        );

        if ($validatePost->fails()) {
            // return response()->json([
            //     'status' => false,
            //     'message' => 'Validation Failed',
            //     'errors' => $validatePost->errors()->all()
            // ], 401);
            $this->errorMessage('Validation Failed',$validatePost->errors()->all());
        }

        $img = $request->image;
        $extention = $img->getClientOriginalExtension(); // Typo fixed
        $imageName = time().'.'.$extention;
        $img->move(public_path().'/uploads/', $imageName);

        $post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $imageName,
        ]);
        
        //*******Short Code Enherit baseController class****
        return $this->sendResponse($post, 'Post Created Successfully');
        
        // Large Code 
        // return response()->json([
        //         'status' => true,
        //         'message' => 'Post Created Successfully',
        //         'post' => $post,
        // ], 200);
    }

    public function show(string $id)
    {
        $data['post'] = Post::select(
            'id',
            'title',
            'description',
            'image'
        )->where(['id'=> $id])->get();

        //*******Short Code Enherit baseController class****
        return $this->sendResponse( $data, 'Single Post show Successfully');
      // Large Code 
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Single Post show Successfully',
        //     'data' => $data,
        // ], 200);
    }

    public function update(Request $request, string $id)
    {
        $validatePost = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif',
            ]
        );

        if ($validatePost->fails()) {
            //**** Short Code Enherit baseController class****
         return $this->errorMessage('Validation Failed',$validatePost->errors()->all());
            // Large Code
            // return response()->json([
            //     'status' => false,
            //     'message' => 'Validation Failed',
            //     'errors' => $validatePost->errors()->all()
            // ], 401);
        }

        $post = Post::find($id);

        if ($request->hasFile('image')) {
            $path = public_path(). '/uploads/';

            if ($post->image && file_exists($path . $post->image)) {
                unlink($path . $post->image);
            }

            $img = $request->image;
            $extention = $img->getClientOriginalExtension(); // Typo fixed
            $imageName = time().'.'.$extention;
            $img->move($path, $imageName);
        } else {
            $imageName = $post->image;
        }

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        
        //*******Short Code Enherit baseController class****
        return $this->sendResponse( $post, 'Post Updated Successfully');

        // Large Code
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Post Updated Successfully',
        //     'post' => $post,
        // ], 200);
    }

    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $filePath = public_path().'/uploads/' . $post->image; // Path fixed

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $post->delete();

       //*******Short Code Enherit baseController class****
       return $this->sendResponse( $post, 'Your Post has been Deleted');

        //Large Code
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Your Post has been Deleted',
        // ], 200);
    }
}



