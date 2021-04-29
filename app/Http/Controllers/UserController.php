<?php

namespace App\Http\Controllers;

use App\Models\User;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('users');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" data-id="' . $data->id . '" class="btn btn-outline-primary btn-sm btn-flat user-edit">Edit</button>';
                    $button .= '<button type="button" data-id="' . $data->id . '" class="btn btn-outline-danger btn-sm ml-2 btn-flat user-delete">Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.user');
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
                    'name' => 'required|string|min:3',
                    'email' => 'required|email',
                ]);
            } else {
                $valid = Validator::make($request->all(), [
                    'name' => 'required|string|min:3',
                    'email' => 'required|email',
                    'password' => 'required|string|min:4',
                ]);
            }
            if ($valid->fails()) {
                $err = $valid->getMessageBag()->toArray();
                foreach ($err as $e => $v) {
                    $err[] = $v;
                }
                return response()->json(['status' => false, 'message' => $err]);
            } else {
                if (strlen($request->id) > 0) {
                    $action = User::find($request->id);
                    $action->name = $request->name;
                    $action->email = $request->email;
                    if (strlen($request->password) > 0) {
                        $action->password = bcrypt($request->password);
                    }
                } else {
                    $action = new User();
                    $action->name = $request->name;
                    $action->email = $request->email;
                    $action->password = bcrypt($request->password);
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
     * Edit a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
                $user = User::find($request->id);
                if ($user) {
                    return response()->json(['status' => true, 'user' => $user]);
                } else {
                    return response()->json(['status' => false]);
                }
            }
        }
    }

    /**
     * destroy a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
                $user = User::find($request->id);

                if ($user) {
                    $user->delete();
                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['status' => false]);
                }
            }
        }
    }

}
