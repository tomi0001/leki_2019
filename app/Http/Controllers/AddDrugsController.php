<?php


namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Services\calendar;
use App\Http\Services\User as user;
use App\Product as Product;
use Illuminate\Support\Facades\Input as Input;

use Auth;
use App\Http\Services\drugs as Drugs;
use Hash;
use DB;
class AddDrugsController
{
    public $error = array();
    
    public function addDrugs() {
        if ( (Auth::check()) ) {
            $Drugs = new Drugs;
            $listGroup = $Drugs->showGroup(Auth::User()->id);
            $listSubstance = $Drugs->showSubstances(Auth::User()->id);
            
            return View("Drugs.Add")->with("listGroup",$listGroup)
                                ->with("listSubstance",$listSubstance);
        }
        
        
    }
    public function addProductAction() {
        if ( (Auth::check()) ) {
            $Drugs = new Drugs;
            $Product = new Product;
            $bool  = $Drugs->checkIfHow(Input::get("price"),Input::get("how"));
            $bool2 = $Drugs->checkProduct(Input::get("name"),Auth::User()->id);
            $bool3 = $Drugs->checkSubstanceArray( Input::get("Substance"),Auth::User()->id);
            if (Input::get("name") == "") {
                array_push($this->error, "Wpisz nazwę");
            }
            if (Input::get("percent") !=  "" and !is_numeric(Input::get("percent"))) {
                array_push($this->error, "Pole procent musi być numeryczne");
            }
            if ($bool2 == false) {
                array_push($this->error, "Juz jest substancja o takiej nazwie");
            }
            if ($bool3 == false) {
                array_push($this->error, "Coś poszło nie tak");
            }
            if ($bool == -2) {
                array_push($this->error, "Musisz wpisać dwa pola czyli cena i za ile");
            }
            else if ($bool == -1) {
                array_push($this->error, "Pole cena musi być numeryczna, a pole za ile całkowita");
            }
            if (count($this->error) != 0) {
                return View("ajax.error_array")->with("error",$this->error);
                
            }
            else {
                $id = $Drugs->saveProduct(Input::get("name"),Auth::User()->id,Input::get("percent"),Input::get("portion"),Input::get("price"),Input::get("how"));
                //saveProduct($name,$id_users,$percent,$portion,$price,$how)
                $Drugs->addForwadindSubstance($id,Input::get("substance"));
                return View("ajax.succes")->with("succes","Dodano pomyslnie produkt");
            }
        }
        
        
    }
    public function addSubstancesAction() {
        if ( (Auth::check()) ) {
            $Drugs = new Drugs;
            $check = $Drugs->checkSubstances(Input::get("name"),Auth::User()->id);
            //var_dump( Input::get("name"));
            $bool = $Drugs->checkGroupArray(Input::get("group"),Auth::User()->id);
            if (Input::get("name") == "") {
                 return View("ajax.error")->with("error","Wpisz nazwę");
            }
            if ($bool == false){
                return View("ajax.error")->with("error","Coś poszło nie tak");
            }
            else if($check == false) {
                return View("ajax.error")->with("error","Już jest substancja o takiej nazwie");
                
                //$Drugs->addSubstances()
            }
            else {
                $Drugs->addSubstances(Input::get("group"),Input::get("equivalent"),Input::get("name"),Auth::User()->id);
                return View("ajax.succes")->with("succes","Pomyslnie dodano substancje");
            }
            
            
        }
        
    }
    public function addGroupAction() {
        if ( (Auth::check()) ) {
            $Drugs = new Drugs;
            if (Input::get("name") == "") {
                return View("ajax.error")->with("error","Wpisz nazwę");
            }
            $bool = $Drugs->addGroup();
            if ($bool == true) {
                return View("ajax.succes")->with("succes","Grupa dodana pomyslnie");
            }
            else {
                return View("ajax.error")->with("error","Już jest Grupa o takiej nazwie wybierz inną");
                
            }
            
        }
        
    }
    
}
    
