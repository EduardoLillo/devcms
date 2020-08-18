<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
class UserController extends Controller
{
    public function __construct (){

		$this->middleware('auth');
		$this->middleware('isadmin');
		$this->middleware('user.status');
    }

    public function getUsers($status){
    	if ($status == 'all'):
    		$user= User::orderBy('id','Desc')->paginate(3);
    	else:
    		$user= User::where('status', $status)->orderBy('id','Desc')->paginate(3);	

    	endif;
		
		$data= ['users' => $user];
		return view('admin.users.home', $data);
    }
    public function getUserEdit($id){

    	$u = User::findOrFail($id);
    	$data = ['u' => $u];
    	return view ('admin.users.user_edit', $data);
    }
    public function getUserBanned($id){
    	$u = User::findOrFail($id);
    	if($u->status == "100"):
    		$u->status = "1";
    		$msg = "Usuario activo nuevamente.";
    	else:
    		$u->status = "100";
    		$msg = "Usuario suspendido con éxito.";
    	endif;

    	if($u->save()):
    		return back()->with('message', $msg)->with('typealert', 'success');
    	endif;

    }
}
