<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Hash;
class UserController extends Controller
{
    private $_request = null;
    private $_modal = null;

    /**
     * Create a new controller instance.
     *
     * @return $reauest, $modal
     */
    public function __construct(Request $request, User $modal)
    {
        $this->_request = $request;
        $this->_modal = $modal;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
        // return response()->json(['users'=>User::get()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd("create");
        // return view({{ view_name }});
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // dd($this->_request->all());

        // $this->validate($this->_request, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => ['required', 'string', 'min:8', 'confirmed'],
        $validator = Validator::make($this->_request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        // old---------------------------------------------------------------------
        $data = $this->_request->except('_token');
        $data = $this->_request->only('name','email');
        $data['password'] = Hash::make($this->_request->password);

        $var = $this->add($this->_modal, $data);
        $token = $var->createToken('auth_token')->plainTextToken;
        // assign role
        if($this->_request->role != null || $this->_request->role =="normaluser")
        {
            $var->assignRole('normaluser');
        }else{
            $var->assignRole('venderuser');
        }
        return response()->json([
            'message'=>"User Created",
            'status'=>'success',
            'user'=>$data,
            'access_token' => $token,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  $this->_modal  $modal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $data = $this->get_by_id($this->_modal, $id);
        $data = User::where('id',$id)->first();
        if($data != null)
        {
            return response()->json([
                'message'=>"User Found",
                'status'=>'success',
                'user'=>$data,
            ]);
        }else{
            return response()->json([
                'message'=>"User Not Found",
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $modal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $data = User::where('id',$user->id)->first();
        if($data != null)
        {
            $data->update([
                'name' => $this->_request->name,
                'email' => $this->_request->email,
                'password' => $this->_request->password ? Hash::make($this->_request->password) : $user->password,
            ]);
            return response()->json([
                'message'=>"User Updated",
                'status'=>'success',
                'user'=>$data,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $modal
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        $data = User::where('id',$user->id)->first();

        if($data != null)
        {
            $data->delete();
            return response()->json([
                'message'=>"user deleted",
                'status'=>"success",
            ]);
        }else{
            return response()->json([
                'message'=>"user not found!",
                'status'=>"error",
            ]);
        }

    }
}