<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Yajra\DataTables\Datatables;
use Validator;

class AjaxController extends Controller
{ 
    function index()
    {
    	return view('ajaxdata.index');
    }

    function getdata()
    {
    	$data = Teacher::select('id','first_name','last_name');
    	return Datatables::of($data)
		->addColumn('action',function($data){
			return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$data->id.'"><i class="glyphicon glyphicon-edit"></i>Edit</a><a href="#" class="btn btn-xs btn-danger delete" id="'.$data->id.'"><i class="glyphicon glyphicon-delete"></i>Delete</a>';
		})
		->make(true);
    }
 
    function postdata(Request $request)
    {
    	$validation = Validator::make($request->all(),[
    		'first_name' => 'required',
    		'last_name' => 'required'
    	]);
    	$error_message = array();
    	$success = '';
    	if ($validation->fails()) 
    	{
    		foreach ($validation->messages()->getMessages() as $key => $value) 
    		{
    			$error_message[] = $value;
    		}
    	}
    	else
    	{
    		if ($request->get('button_action')=="insert") 
    		{
    			$data = new Teacher([
    				'first_name' => $request->get('first_name'),
    				'last_name' => $request->get('last_name')
    			]);
    			$data->save();

    			$success = '<div class="alert alert-success">Data Added Successfully</div>';
    		} 

			if ($request->get('button_action') == "update") {
				$teacher = Teacher::find($request->get('edit_id'));
 
				$teacher->first_name = $request->get('first_name');
				$teacher->last_name = $request->get('last_name');
				$teacher->save();
				$success = '<div class="alert alert-success">Data Updated</div>';
 
			 }
    	}

    	$output = array(
    		'error' => $error_message,
    		'success' => $success
    	);
    	echo json_encode($output);
    }

	function fetchdata(Request $request)
	{
		$id = $request->get('teacher_id');

		$teacher = Teacher::find($id);

		$output = array(
			'first_name' => $teacher->first_name,
			'last_name' => $teacher->last_name,
			'id' => $teacher->id
		);
		echo json_encode($output);
	}

	function removedata(Request $request)
	{
		$teacher = Teacher::find($request->get('teacher_id'));
		if($teacher->delete()){
			echo "Data Deleted";
		}
	}
	 
}