<?php

namespace App\Http\Services;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
//use App\calendar;
use Illuminate\Support\Facades\Input as Input;
use Auth;

use Hash;
use DB;
use App\User as Users;
class User
{
    public $error = array();
    public function checkFormRegister() {
        if (Input::get('login') == "") {
            array_push($this->error, "Nie masz podane Loginu");
        }
        if (Input::get('password') !=  Input::get('password_confirm')) {
            array_push($this->error, "Podane hasła różnią się");
        }
        elseif (Input::get('password') == "" or Input::get('password_confirm') == "") {
            array_push($this->error, "Musisz uzupełnić dwa hasła");
        }
        else if (strlen(Input::get("password")) < 5 ) {
            array_push($this->error, "Hasło musi mieć minimum 5 znaków");
            
        }
        if ( is_numeric(Input::get("start_day"))  and  !(Input::get("start_day") >= 0 and Input::get("start_day") <= 23) ) {
            array_push($this->error, "Początek dnia musi być liczbą całkowitą i w zakresie od 0 do 23");
            
        }
        if (Input::get('email') == "") {
            array_push($this->error, "Nie masz podane Maila");
        }
        elseif (!strstr( Input::get('email') ,"@")) {
            array_push($this->error, "Błędny adres email");
        }
        if ($this->ifUserIsset(Input::get("login"))) {
            array_push($this->error, "Już jest użytkownik o takim loginie");
            
        }
        if ($this->ifEmailIsset(Input::get("email"))) {
            array_push($this->error, "Już jest użytkownik o takim emailu");
            
        }

        

        
        
        
    }
    public function saveUser() {
        $userRegister = new Users;
        $userRegister->login = Input::get("login");
        $userRegister->password = Hash::make(Input::get("password"));
        $userRegister->email = Input::get("email");
        $userRegister->start_day = Input::get("start_day");
        $userRegister->save();
        
    }
    private function ifUserIsset($user) {
        $obiekt_user = new Users;
        $login = $obiekt_user->where("login",$user)->first();
        return $login;
        
    }
    private function ifEmailIsset($email) {
        $obiekt_user = new Users;
        $mail = $obiekt_user->where("email",$email)->first();
        return $mail;
        
    }
    //public function 
    

}