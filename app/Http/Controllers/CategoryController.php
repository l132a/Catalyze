<?php

namespace App\Http\Controllers;

use App\Models\Category;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('categories');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm btn-flat category-edit">Edit</button>';
                    $button .= '<button type="button" data-id="' . $data->id . '" class="btn btn-outline-danger btn-sm ml-2 btn-flat category-delete">Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.category');
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

            $valid = Validator::make($request->all(), [
                'category' => 'required|string|min:3',
                'slug' => 'required|string',
            ]);

            if ($valid->fails()) {
                $err = $valid->getMessageBag()->toArray();
                foreach ($err as $e => $v) {
                    $err[] = $v;
                }
                return response()->json(['status' => false, 'message' => $err]);
            } else {
                if (strlen($request->id) > 0) {
                    $action = Category::find($request->id);
                    $action->category = $request->category;
                    $action->slug = Str::slug($request->slug, '-');
                } else {
                    $action = new Category();
                    $action->category = $request->category;
                    $action->slug = Str::slug($request->slug, '-');
                }
                if ($action->save()) {
                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['status' => false]);
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
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $category = Category::select('id', 'category')->get();
            if ($category) {
                return response()->json(['status' => true, 'category' => $category]);
            } else {
                return response()->json(['status' => false]);
            }
        }
    }

    /**
     * Edit the form for editing the specified resource.
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
                $category = Category::find($request->id);
                if ($category) {
                    return response()->json(['status' => true, 'category' => $category]);
                } else {
                    return response()->json(['status' => false]);
                }
            }
        }
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
                $category = Category::find($request->id);

                if ($category) {
                    $category->delete();
                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['status' => false]);
                }
            }
        }
    }
}
