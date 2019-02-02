<?php


namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Services\calendar;
use App\Http\Services\User as user;
use Illuminate\Support\Facades\Input as Input;

use Auth;
use App\Http\Services\drugs as Drugs;
use Hash;
use DB;
class MainDrugsController
{   
        public $error = array();
        public function addDrugsAction() {
            $Drugs = new Drugs;
            $date = $Drugs->checkDate(Input::get("date"),Input::get("time"));
            //$Drugs->checkDescriptions(Input::get("desciption"));
            if (Input::get("name") == "") {
                array_push($this->error, "Wpisz nazwę");
                //return View("ajax_error")->with("error","Wpisz nazwę");
            }
            if ($date == -1 or $date == -2) {
                array_push($this->error, "Błędna data");
            }
            if (Input::get("dose") == "") {
                array_push($this->error, "Uzupełnij pole dawka");
            }
            else if (!is_numeric(Input::get("dose"))) {
                array_push($this->error, "Pole dawka musi być numeryczne");
            }
            //if (!$Drugs->checkIfIdIsForUser(Input::get("name")) ) {
              //  array_push($this->error, "Wystąpił jakiś błąd");
            //}
            if (count($this->error) != 0) {
                return View("ajax_error_array")->with("error",$this->error);
            }
            else {
                $price = $Drugs->sumPrice(Input::get("dose"),Input::get("name"));
                $Drugs->addDrugs($Drugs->date,$price);
                //print $Drugs->date;
                return View("ajax_succes")->with("succes","Pomyslnie dodano");
            }
            
        }
    
    
}