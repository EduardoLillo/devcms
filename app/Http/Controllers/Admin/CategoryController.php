<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator,Str;
use App\Http\Models\Category;


class CategoryController extends Controller
{
    public function __construct (){

			$this->middleware('auth');
			$this->middleware('isadmin');
			$this->middleware('user.status');
	    }
	public function getHome($module){
		$cats = Category::where('module', $module)->orderBy('name','Asc')->paginate(3);
		$data = ['cats'=>$cats];
		//	return view('admin.categories.home',$data);
		return view('admin.categories.home', $data);
		}

		/*public function getCategories(){
		$category= Category::orderBy('id','Desc')->get();
		$data= ['categories'=> $user];
		return view('admin.categories.home', $data);
    }*/

	public function postCategoryAdd( Request $request){
		$rules = [
    		'name'=>'required',
			'icon'=>'required',	
    	];

    	$messages= [
    		'name.required'=>'Se requiere un nombre para la Categoria',
    		'icon.required'=>'debe elejir un icono para la Categoria'
    	];

    	$validator = Validator::make($request->all(), $rules, $messages);
    	if($validator->fails()):
    			return back()->withErrors($validator)->with('message','Se ha producido un error')->with('typealert', 'danger');
	    	else:
	    		$category = new Category;
	    		$category->module = $request->input('module');
	    		$category->name = e($request->input('name'));	
	    		$category->slug = Str::slug($request->input('name'));
	    		$category->icono = e($request->input('icon'));
	    		if($category->save()):
				return back()->with('message','La Categoria se creo con exito')->with('typealert','success');
	    		endif;
    	endif;
    	}

	public function getCategoryEdit($id){
		$cat = Category::find($id);
		$data = ['cat'=>$cat];
		return view('admin.categories.edit', $data);

		}

	public function postCategoryEdit(Request $request,$id){	

		$rules = [
    		'name'=>'required',
			'icon'=>'required',	
    	];

    	$messages= [
    		'name.required'=>'Se requiere un nombre para la Categoria',
    		'icon.required'=>'debe elejir un icono para la Categoria'
    	];

    	$validator = Validator::make($request->all(), $rules, $messages);
    	if($validator->fails()):
    			return back()->withErrors($validator)->with('message','Se ha producido un error')->with('typealert', 'danger');
	    	else:
	    		$category = Category::find($id);
	    		$category->module = $request->input('module');
	    		$category->name = e($request->input('name'));	
	    		$category->slug = Str::slug($request->input('name'));
	    		$category->icono = e($request->input('icon'));
	    		if($category->save()):
				return back()->with('message','La Categoria se creo con exito')->with('typealert','success');
	    		endif;
    	endif;	
	}
	public function getCategoryDelete($id){
		$category = Category::find($id);
		if ($category->delete()):
		return back()->with('message','La Categoria se elimino con exito')->with('typealert','success');
	endif;
	}
}
