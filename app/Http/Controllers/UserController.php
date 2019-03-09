<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Services\calendar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Services\User as User2;
use Illuminate\Support\Facades\Input as Input;
use Auth;
use Hash;
use DB;
use App\User as Users;
class UserController 
{
    public function register() {
        
        return view("User.Register");
    }
    
    public function registerSubmit() {
        $User = new User2;
        $User->CheckFormRegister();
        if (count($User->error) != 0) {
            return Redirect("/User/Register")->with("error",$controllerUser->error)
                    ->withInput();
        }
        else {
            $User->saveUser();
            return Redirect("/User/Login")->with("succes","Możesz się teraz zalogować");
        }
        
    }
    public function logout() {
        Auth::logout();
        return redirect("/User/Login")->with("error","Wylogowałeś się");
    }
    public function login() {
        return View("User.Login");
        
    }
    public function changePassword() {
        $User = new User2;
        if ( (Auth::check()) ) {
            $bool = $User->checkPassword();
            if (count($User->error) != 0) {
                return View("ajax.error_array")->with("error",$User->error);
            }
            else {
                $User->setPassword($bool);
                return View("ajax.succes")->with("succes","Pomyślnie edytowałeś dane");
            }
        }
        
    }
    public function loginAction() {
        
        $user = array(
            "login" => Input::get("login"),
            "password" => Input::get("password")
            
        );
        if (Input::get('login') == "" or Input::get('password') == "" ) {
            return Redirect('/User/Login')->with('error','Uzupełnij pole login i hasło');
        }
        if (Auth::attempt($user) ) {
            return Redirect("/Main");
        }
        else {
            return Redirect('/User/Login')->with('error','Nie prawidłowy login lub hasło');
        }
    }
}