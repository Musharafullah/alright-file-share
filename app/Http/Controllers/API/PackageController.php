<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use Validator;
class PackageController extends Controller
{
    private $_request = null;
    private $_modal = null;

    /**
     * Create a new controller instance.
     *
     * @return $reauest, $modal
     */
    public function __construct(Request $request, Package $modal)
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

        $allpackages = $this->get_all($this->_modal);
        if($allpackages)
        {
            $message = "Records found!";
        }else{
            $message = "Records not found!";
        }
        return response()->json(['data' => $allpackages,'message' => $message]);

    }

    //store packages
    public function store()
    {

        $validator = Validator::make($this->_request->all(),[
            'name' => 'required',
            'quota' => 'required',
            'price' => 'required',
            'duration' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $data = $this->_request->except('_token');
        $data = $this->_request->only('name','quota','duration','price');
        $var = $this->add($this->_modal, $data);

        return response()->json(['data' => $var,'message' => "package inserted successfully!"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  $this->_modal  $modal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data = $this->get_by_id($this->_modal, $id);

        if($data)
        {
            $message = "Records found!";
        }else{
            $message = "Records not found!";
        }
        return response()->json(['data' => $data,'message' => $message]);
    }


    /**
     * for update package
     */
    public function update($id)
    {

        $validator = Validator::make($this->_request->all(),[
            'name' => 'required',
            'quota' => 'required',
            'price' => 'required',
            'duration' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }
        $check= $this->get_by_id($this->_modal, $id);

        if($check)
        {
            $data = $this->_request->only('name','quota','duration','price');
            $check->update($data);
            $message = "package updated successfully!";
            return response()->json(['data' => $check,'message' => $message]);
        }else{
            $message = "Records not found!";
            return response()->json(['message' => $message]);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Package  $modal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->delete($this->_modal, $id);
        return redirect()->route('{{ routeName }}');
    }
}