<?php

namespace App\Http\Controllers;

//use Illuminate\Support\Facades\Auth,Mail;
use Illuminate\Http\Request;
use Validator,Hash,Str,Auth,Mail;
use App\User;
use App\Mail\UserSendRecover;
use App\Mail\UserSendReset;


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

    public function getRecover(){
        return view('connect.recover');
    }

    public function postRecover(Request $request){

        $rules = [
            'email'=>'required|email',
            
        ];

        $messages= [
           
            'email.required'=>'Su correo electronico es requerido',
            'email.email'=>'El formato de su coreo electronico es incorrecto',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()):
                return back()->withErrors($validator)->with('message','Se ha producido un error')->with('typealert', 'danger');
        else:
            $user = User::where('email', $request->input('email'))->count();
            if ($user == "1"):
                $user = User::where('email', $request->input('email'))->first();
                $code = rand(100000, 999999);
                $data = ['name'=> $user->name, 'email'=>$user->email, 'code' => $code];
                $u = User::find($user->id);
                $u->password_code = $code;
                if($u->save()):
                Mail::to($user->email)->send(new UserSendRecover($data));
                return redirect('/reset?email='.$user->email)->with('message', 'Ingrese el codigo que le hemos enviado a su correo electronico')->with('typealert', 'success');
                endif; 
            else:
                return back()->with('message', 'Este correo electronico no existe')->with('typealert', 'danger');
            endif;
        endif;
    }
    public function getReset(Request $request){
        $data = ['email' => $request->get('email')];
        return view('connect.reset', $data);
    }

    public function postReset(Request $request){

         $rules = [
            'email'=>'required|email',
            'code'=>'required'
            
        ];

        $messages= [
           
            'email.required'=>'Su correo electronico es requerido',
            'email.email'=>'El formato de su coreo electronico es incorrecto',
            'code.required'=>'El codigo de verificaion es requerido'       
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()):
                return back()->withErrors($validator)->with('message','Se ha producido un error')->with('typealert', 'danger');
        else:
            $user = User::where('email', $request->input('email'))->where('password_code', $request->input('code'))->count();
            if($user == "1" ):
                $user = User::where('email', $request->input('email'))->where('password_code', $request->input('code'))->first();
                $new_password = Str::random(8);
                $data = ['name'=> $user->name, 'email'=>$user->email, 'password' => $new_password];
                $user->password = Hash::make($new_password);
                $user->password_code = null;
                if($user->save()):
                     Mail::to($user->email)->send(new UserSendReset($data));
                    return redirect('/login')->with('message', 'La contraseña fue reestablesida con exito, ahora puede iniciar sesion')->with('typealert', 'success');
                endif;
            else:
                return back()->with('message', 'El corrreo electronico o Codigo de recupercion son erroneos')->with('typealert', 'danger');
            endif;
        endif;
    }
}
