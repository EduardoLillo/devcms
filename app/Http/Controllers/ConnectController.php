<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator,Hash;
use App\User;


class ConnectController extends Controller
{
    public function __construct(){
    	$this->middleware('guest')->except(['getLogout']);
    }
    public function getLogin(){
    	return view('connect.login');
    }
    public function getLogout(){
        $status = Auth::user()->status;
    	Auth::logout();
        if ($status == "100"):
            return redirect('/login')->with('message','Usuario Suspendido')->with('typealert', 'danger');
        else:
            return redirect('/');
        endif;    

    }

    public function postLogin(Request $request){
		$rules =[
			'email'=>'required|min:8',
			'password'=>'required|min:8'
		];
		$messages=[
			'email.required'=>'Su correo electronico es requerido',
    		'email.email'=>'El formato de su coreo electronico es incorrecto',
			'password.required'=>'Por favor escriba una contraseña',
    		'password.min'=>'la contraseña debe tener al menos 8 caracteres'

		];

		$validator = Validator::make($request->all(), $rules, $messages);
    	if($validator->fails()):
    			return back()->withErrors($validator)->with('message','Se ha producido un error')->with('typealert', 'danger');
    	else:
            if (Auth::attempt(['email'=>$request->input('email'),'password'=>$request->input('password')],true)):
                if(Auth::User()->status == "100"):
                    return redirect('/logout');
		        else:
                    return redirect('/');
                endif;
/*            $credentials = $request->only('email', 'password');

    		if (Auth::attempt($credentials)): 
            // Authentication passed...
           	 return redirect()->intended('/');*/
            else:
	        	return back()->with('message','Se ha producido un error , el email o la contraseña son incorrectos')->with('typealert', 'danger');
	        endif;
    	endif;

    }

   /* public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('dashboard');
        }
    }*/


    public function getRegister(){
    	return view('connect.register');
    }

    public function postRegister(Request $request){
    	$rules = [
    		'name'=>'required',
    		'lastname'=>'required',
			'email'=>'required|email|unique:App\User,email',
			'password'=>'required|min:8',
			'cpassword'=>'required|min:8|same:password'
    	];

    	$messages= [
    		'name.required'=>'Su nombre es requerido',
    		'lastname.required'=>'Su apellido es requerido',
    		'email.required'=>'Su correo electronico es requerido',
    		'email.email'=>'El formato de su coreo electronico es incorrecto',
    		'email.unique'=>'Ya existe un usuario con este correo',
    		'password.required'=>'Por favor escriba una contraseña',
    		'password.min'=>'la contraseña debe tener al menos 8 caracteres',
    		'cpassword.required'=>'Es necesario confirmar la contraseña',
    		'cpassword.min'=>'la confirmacion de la  contraseña debe tener al menos 8 caracteres',
    		'cpassword.same'=>'Las contraseñas no coinciden'
    	];

    	$validator = Validator::make($request->all(), $rules, $messages);
    	if($validator->fails()):
    			return back()->withErrors($validator)->with('message','Se ha producido un error')->with('typealert', 'danger');
    	else:
    		$user = new User;
    		$user->name =e($request->input('name'));
    		$user->lastname =e($request->input('lastname'));
    		$user->email =e($request->input('email'));
    		$user->password = Hash::make($request->input('password'));

    		if($user->save()):
			return redirect('/login')->with('message','Su usuario se creo con exito, ahora puede iniciar sesion')->with('typealert','success');
    		endif;
    	endif;
    	  	
    }
}
