<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Services\calendar;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Services\User as User2;
use App\Http\Services\hashs as Hashs;
use Illuminate\Support\Facades\Input as Input;
use Auth;
use Hash;
use DB;
use Cookie;
//use Response;
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
            return Redirect("/User/Register")->with("error",$User->error)
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
    public function login(Request $request) {
        //Cookie::queue(Cookie::make('name', '1212', 60));
        //print Cookie::get("name");
//        print $request->cookie('hash');
        // $response = new Response('Hello World');
      //$response->withCookie(cookie('hash', 'virat', time() + 3000));
        //print cookie('hash', 'value', 60);
        //Cookie::make('hash', 'sa',time() + 400);
        //print Cookie::get("uzytkownik");
        //setcookie("uzytkownik", "Marian", time()+3600);
        //$minutes = 60;
        
      //$response = new Response('hash');
      //$response->withCookie(cookie('hash', 'MyValue', $minutes));
        //print $_COOKIE['hash'];
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
    /*
    public function loginActionDr() {
        $Hash = new Hashs;
        $check = $Hash->checkHashDr();
        if (Input::get('login') == "" or Input::get('hash') == "" ) {
            return Redirect('/User/Login')->with('errorDr','Uzupełnij pole login i hasło');
        }
        else if ($check == false) {
            return Redirect('/User/Login')->with('errorDr','Nie prawidłowy login lub hasło');
        }
        else {
            $data = $Hash->selectData();
            //\Cookie::make('id', $data[0], 3600);
            //\Cookie::make('hash', $data[1], 3600);
            Cookie::queue(Cookie::make('id', $data[0], 60));
            Cookie::queue(Cookie::make('hash', $data[1], 60));
            //setcookie("id", $data[0], time() + 3600);
            //setcookie("hash", $data[1], time() + 3600);
            return Redirect("/Main");
        }
    }
     * 
     */
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