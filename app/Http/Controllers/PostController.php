<?php

namespace App\Http\Controllers;

use App\Models\Post;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('posts');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    return "<img src='" . asset('images/' . $data->image) . "' width='80px'>";
                })
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm btn-flat post-edit">Edit</button>';
                    $button .= '<button type="button" data-id="' . $data->id . '" class="btn btn-outline-danger btn-sm ml-2 btn-flat post-delete">Delete</button>';
                    return $button;
                })
                ->addColumn('status', function ($data) {
                    return $data->status == 1 ? '<span style="color:green">Published</span>' : '<span style="color:red">Draft</span>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
        return view('backend/post');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            if (strlen($request->id) > 0) {
                $valid = Validator::make($request->all(), [
                    'title' => 'required|string|min:3',
                    'content' => 'required|string',
                    'category_id' => 'required|integer',
                    'status' => 'required',
                ]);
            } else {
                $valid = Validator::make($request->all(), [
                    'title' => 'required|string|min:3',
                    'content' => 'required|string',
                    'category_id' => 'required|integer',
                    'status' => 'required',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ]);
            }

            if ($valid->fails()) {
                $err = $valid->getMessageBag()->toArray();
                foreach ($err as $e => $v) {
                    $err[] = $v;
                }
                return response()->json(['status' => false, 'message' => $err]);
            } else {
                $upload_status = true;
                if ($request->image) {
                    $image_name = time() . '.' . $request->image->extension();
                    $upload_status = $request->image->move(public_path('images'), $image_name) ? true : false;
                }

                if ($upload_status) {
                    if (strlen($request->id) > 0) {
                        $action = Post::find($request->id);
                        $action->title = $request->title;
                        $action->slug = Str::slug($request->title, '-');
                        $action->content = $request->content;
                        $action->category_id = $request->category_id;
                        if ($request->image) {
                            $action->image = $image_name;
                        }
                        $action->status = $request->status;
                        $action->user_id = auth()->user()->id;
                    } else {
                        $action = new Post();
                        $action->title = $request->title;
                        $action->slug = Str::slug($request->title, '-');
                        $action->content = $request->content;
                        $action->category_id = $request->category_id;
                        if ($request->image) {
                            $action->image = $image_name;
                        }
                        $action->status = $request->status;
                    }
                    if ($action->save()) {
                        return response()->json(['status' => true]);
                    } else {
                        return response()->json(['status' => false]);
                    }
                } else {
                    return response()->json(['status' => false, 'message' => 'Failed']);
                }
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $valid = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);
            if ($valid->fails()) {
                $err = $valid->getMessageBag()->toArray();
                foreach ($err as $e => $v) {
                    $err[] = $v;
                }
                return response()->json(["message" => $err, 'status' => false]);
            } else {
                $post = DB::table('posts')
                    ->select('posts.*', 'categories.category')
                    ->join('categories', 'categories.id', '=', 'posts.category_id')
                    ->where('posts.id', $request->id)
                    ->first();
                if ($post) {
                    return response()->json(['status' => true, 'post' => $post]);
                } else {
                    return response()->json(['status' => false]);
                }
            }
        }
    }

    protected function uriDecoder($url)
    {
        return preg_replace("/[^A-Za-z ]/", "", strip_tags(trim(str_replace('-', ' ', $url))));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $valid = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);
            if ($valid->fails()) {
                $err = $valid->getMessageBag()->toArray();
                foreach ($err as $e => $v) {
                    $err[] = $v;
                }
                return response()->json(["message" => $err, 'status' => false]);
            } else {
                $post = Post::find($request->id);

                if ($post) {
                    $post->delete();
                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['status' => false]);
                }
            }
        }
    }

    /**
     * All the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        $limit = $request->input('limit');
        $offset = $request->input('offset');
        $total = Post::count();
        $posts = DB::table('posts')->offset($offset)->limit($limit)->get();
        if ($posts) {
            return response()->json(['status' => true, 'data' => $posts, 'total' => $total]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    /**
     * Get the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $post = DB::table('posts')
            ->select('posts.*', 'categories.category')
            ->join('categories', 'categories.id', '=', 'posts.category_id')
            ->where('posts.id', $id)
            ->first();
        if ($post) {
            return response()->json(['status' => true, 'data' => $post]);
        } else {
            return response()->json(['status' => false]);
        }
    }
}
