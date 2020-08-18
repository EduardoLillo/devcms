<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Product, App\Http\Models\Category, App\Http\Models\PGallery;
use Validator,Str,Config,Image;

class ProductController extends Controller
{
	 public function __construct (){

		$this->middleware('auth');
		$this->middleware('isadmin');
		$this->middleware('user.status');
	  }

	public function getHome(){
		$products = Product::with('cat')->OrderBy('id','Desc')->paginate(3);
		$data = ['products'=>$products];
	return view('admin.products.home', $data);
	}

	public function getProductAdd(){
		$cats = Category::where('module','0')->pluck('name','id');
		$data =['cats'=> $cats];
	return view ('admin.products.add', $data);
	}
	

	public function getProductEdit($id){
		$p = Product::findOrFail($id);
		$cats = Category::where('module','0')->pluck('name','id');
		$data =['cats'=> $cats,'p'=>$p];
		return view ('admin.products.edit', $data);
	}

	public function postProductAdd(Request $request){	

		$rules = [
    		'name'=>'required',
			'img'=>'required',	
			'price'=>'required',
			'content'=>'required',
    	];

    	$messages= [
    		'name.required'=>'Se requiere un nombre para el Producto',
    		'img.required'=>'Seleccione una imagen destacada',
    		'img.image'=>'El archivo no es una imagen',
    		'price.required'=>'Ingrese el precio del Producto',
    		'content.required'=>'Debe agregar una descripcion',	
    	];

    	$validator = Validator::make($request->all(), $rules, $messages);
    	if($validator->fails()):
    			return back()->withErrors($validator)->with('message','Se ha producido un error')->with('typealert', 'danger')->withInput();
	    	else:
	    		$path = '/'.date('y-m-d');
	    		$fileExt = trim($request->file('img')->getClientOriginalExtension());
	    		$upload_path = Config::get('filesystems.disks.uploads.root');
	    		$name = Str::slug(str_replace($fileExt, '', $request->file('img')->getClientOriginalName()));
	    		$filename = rand(1,999).'-'.$name.'.'.$fileExt;

	    		$file_file = $upload_path.'/'.$path.'/'.$filename;

	    		$product = new Product;
	    		$product->status = '0';
	    		$product->name = e($request->input('name'));
	    		$product->slug = Str::slug($request->input('name'));
	    		$product->category_id = $request->input('category_id');
	    		$product->file_path = date('y-m-d');
	    		$product->image = $filename;
	    		$product->price = $request->input('price');
	    		$product->in_discount = $request->input('in_discount');
	    		$product->discount = $request->input('discount');
	    		$product->content = e($request->input('content'));
	    		
	    		if($product->save()):
	    			if($request->hasFile('img')):
	    				$fl = $request->img->storeAs($path, $filename, 'uploads');
						$img = Image::make($file_file);
						$img->fit(256, 256, function($constraint){
							$constraint->upsize();							
						});
						$img->save($upload_path.'/'.$path.'/t_'.$filename);
					endif;
				return redirect('/admin/products')->with('message','El Producto se creo con exito')->with('typealert','success');
	    	endif;
    	endif;	
	}
	public function postProductEdit( $id,Request $request){

		$rules = [
    		'name'=>'required',	
			'price'=>'required',
			'content'=>'required',
    	];

    	$messages= [
    		'name.required'=>'Se requiere un nombre para el Producto',
    		'img.image'=>'El archivo no es una imagen',
    		'price.required'=>'Ingrese el precio del Producto',
    		'content.required'=>'Debe agregar una descripcion',
    		
    	];

    	$validator = Validator::make($request->all(), $rules, $messages);
    	if($validator->fails()):
    			return back()->withErrors($validator)->with('message','Se ha producido un error')->with('typealert', 'danger')->withInput();
	    	else:

	    		$product = Product::findOrFail($id);
	    	/* ipp= path de imagen previa*/
	    		$ipp = $product->file_path;
	    	/* ipp= imagen previa*/
	    		$ip = $product->image;

	    		$product->status = $request->input('status');
	    		$product->name = e($request->input('name'));
	    		$product->category_id = $request->input('category_id');
	    		
	    		if($request->hasFile('img')):
		    		$path = '/'.date('y-m-d');
		    		$fileExt = trim($request->file('img')->getClientOriginalExtension());
		    		$upload_path = Config::get('filesystems.disks.uploads.root');
		    		$name = Str::slug(str_replace($fileExt, '', $request->file('img')->getClientOriginalName()));
		    		$filename = rand(1,999).'-'.$name.'.'.$fileExt;
					$file_file = $upload_path.'/'.$path.'/'.$filename;
					$product->file_path = date('y-m-d');
					$product->image = $filename;
	    		endif;

	    		$product->price = $request->input('price');
	    		$product->in_discount = $request->input('in_discount');
	    		$product->discount = $request->input('discount');
	    		$product->content = e($request->input('content'));
	    		
	    		if($product->save()):
	    			if($request->hasFile('img')):
	    				$fl = $request->img->storeAs($path, $filename, 'uploads');
						$img = Image::make($file_file);
						$img->fit(256, 256, function($constraint){
							$constraint->upsize();							
						});
						$img->save($upload_path.'/'.$path.'/t_'.$filename);
						unlink($upload_path.'/'.$ipp.'/'.$ip);
						unlink($upload_path.'/'.$ipp.'/t_'.$ip);
					endif;
				return back()->with('message','Actualizado con exito')->with('typealert','success');
	    	endif;
    	endif;	


	}

	public function postProductGalleryAdd($id,Request $request){

		$rules = [
    		'file_image'=>'required'
    	];

    	$messages= [
    		'file_image.required'=>'Seleccione una imagen'
    	];

    	$validator = Validator::make($request->all(), $rules, $messages);
    	if($validator->fails()):
    		return back()->withErrors($validator)->with('message','Se ha producido un error')->with('typealert', 'danger')->withInput();
	    else:
	    	if($request->hasFile('file_image')):
		    		$path = '/'.date('y-m-d');
		    		$fileExt = trim($request->file('file_image')->getClientOriginalExtension());
		    		$upload_path = Config::get('filesystems.disks.uploads.root');
		    		$name = Str::slug(str_replace($fileExt, '', $request->file('file_image')->getClientOriginalName()));
		    		$filename = rand(1,999).'-'.$name.'.'.$fileExt;
					$file_file = $upload_path.'/'.$path.'/'.$filename;

					$g= new PGallery;
					$g->product_id =$id;
					$g->file_path = date('y-m-d');
					$g->file_name = $filename;

					if($g->save()):
		    			if($request->hasFile('file_image')):
		    				$fl = $request->file_image->storeAs($path, $filename, 'uploads');
							$img = Image::make($file_file);
							$img->fit(256, 256, function($constraint){
								$constraint->upsize();							
							});
							$img->save($upload_path.'/'.$path.'/t_'.$filename);
							
						endif;
						return back()->with('message','Imagen subida con exito')->with('typealert','success');
	    			endif;
			endif;
	    endif;
	}

	public function getProductGalleryDelete($id, $gid){
    	$g = PGallery::findOrFail($gid);
    	$path = $g->file_path;
    	$file = $g->file_name;
    	$upload_path = Config::get('filesystems.disks.uploads.root');
    	if ($g->product_id != $id) {
    		return back()->with('message','La Imagen no se puede eliminar')->with('typealert','danger');
		}else{
			if ($g->delete()):
				unlink($upload_path.'/'.$path.'/'.$file);
				unlink($upload_path.'/'.$path.'/t_'.$file);
				return back()->with('message','La Imagen se elimino con exito')->with('typealert','success');
			endif;	
			}
    }
}
