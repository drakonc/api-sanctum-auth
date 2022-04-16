<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{

    public function listBlog(){
        $user_id = auth()->user()->id;

        $blogs = Blog::where("user_id", $user_id)->get();

        return response()->json([
            "status" => 1,
            "msg" => "Listado De Blogs Por Usuario",
            "data" => $blogs
        ]);
    }

    public function showBlog($id){
        $user_id = auth()->user()->id;
        $blog = Blog::where( ["id" => $id, "user_id" => $user_id ])->first();
        if(isset($blog->id)){
            return response()->json([
                "status" => 1,
                "msg" => "Blog del Usuario",
                "data" => $blog
            ]);
        }else {
            return response()->json([
                "status" => 0,
                "msg" => "Blog del Usuario no existe",
            ],404);
        }
    }
    
    public function createBlog(Request $request){
        $user_id = auth()->user()->id;
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        $blog = new Blog();
        $blog->user_id = $user_id;
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->save();

        return response()->json([
            "status" => 1,
            "msg" =>  "Registro de Blog Exitoso"
        ]);
    }

    public function updateBlog(Request $request, $id){
        $user_id = auth()->user()->id;
        if ( Blog::where( ["user_id"=>$user_id, "id" => $id] )->exists() ) {                        
            $blog = Blog::find($id);
            $blog->title = isset($request->title) ? $request->title : $blog->title;    
            $blog->content = isset($request->content) ? $request->content : $blog->content;                
            $blog->save();
            return response()->json([
                "status" => 1,
                "msg" => "Blog actualizado correctamente."
            ]);
        }else{
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Blog"
            ], 404);
        }
    }

    public function deleteBlog($id){
        $user_id = auth()->user()->id;
        if( Blog::where( ["id" => $id, "user_id" => $user_id ])->exists() ){
            $blog = Blog::where( ["id" => $id, "user_id" => $user_id ])->first();
            $blog->delete();
            return response()->json([
                "status" => 1,
                "msg" => "El blog fue eliminado correctamente."
            ]);
        }else{
             return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Blog"
            ], 404);
        }
    }

}
