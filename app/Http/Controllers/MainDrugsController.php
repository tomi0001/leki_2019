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
                //print $price;
                //print $Drugs->date;
                $Drugs->addDrugs($Drugs->date,$price);
                //print $Drugs->date;
                return View("ajax_succes")->with("succes","Pomyslnie dodano");
            }
            
        }
     public function addDescriptionsAction() {
         $Drugs = new Drugs;
         if (Input::get("description") == "") {
             return View("ajax_error")->with("error","Musisz coś wpisać");
         }
         else {
             $Drugs->addDescription(Input::get("id_use"),date("Y-m-d H:i:s"));
             return View("ajax_succes")->with("succes","Pomyslnie dodano");
         }
         //print str_replace("\n","<br>",Input::get("descriptions"));
         
     }
     public function deleteDrugs() {
         $Drugs = new Drugs;
         $bool = $Drugs->checkDrugs(Auth::User()->id,Input::get("id"));
         if ($bool == true) {
             $Drugs->deleteDescription(Input::get("id"));
             $Drugs->deleteDrugs(Input::get("id"),Auth::User()->id);
             //print "s";
         }
     }
     public function sumAverage() {
         $Drugs = new Drugs;
         $bool = $Drugs->checkDrugs(Auth::User()->id,Input::get("id"));
         if ($bool == true) {
             $list = $Drugs->returnIdProduct(Input::get("id"));
             $date = $Drugs->returnDateDrugs(Input::get("id"));
             $hourDrugs = $Drugs->sumAverage($list,$date);
             //foreach ($lista as $s) {
               //  print $s->id_products . " ";
             //}
             //print "s";
             $array = array();
             for ($i=0;$i < count($hourDrugs);$i++) {
                 //print $lista[$i][0] . "<Br>";
                 //print $lista[$i][1] . "<Br>";
                 
                 //print $lista[$i][2] . "<Br>";
                $array[$i] = $Drugs->sumDifferentDay($hourDrugs[$i][1],$hourDrugs[$i][2]);
                 
             }
             //var_dump($lista);
             //$Drugs->sumAverage(Input::get("id"));
             return View("ajax_sum_average")->with("arrayDay",$array)->with("hourDrugs",$hourDrugs);
         }
         
     }
     public function showDescriptionsAction() {
         $Drugs = new Drugs;
         $list = $Drugs->selectDescription(Input::get("id"));
         //print Input::get("id");
         return View("show_description")->with("list",$list);
         
     }
     public function sumBenzo() {
         $Drugs = new Drugs;
         //$portion = $Drugs->selectPortion(Input::get("idDrugs"));
         $equivalent = $Drugs->selectEquivalent(Input::get("id"));
         //$Drugs->sumEquivalent($listDrugs)
         //$result = $Drugs->calculateEquivalent($portion->portion,$equivalent,10);
         $result = $Drugs->calculateEquivalent(Input::get("equivalent"),10,$equivalent);
         //$result = $Drugs->calculateEquivalent(0.5,0.5,10);
         //$result2 = $Drugs->calculateEquivalent($result,10,1);
         return View("ajax_equivalent_benzo")->with("result",$result);
         //print $result;
     }
}