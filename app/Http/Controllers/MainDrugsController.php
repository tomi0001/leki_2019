<?php


namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Services\calendar;
use App\Http\Services\User as user;
use Illuminate\Support\Facades\Input as Input;
use App\Http\Services\hashs as Hashs;
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
            if (Input::get("name") == "") {
                array_push($this->error, "Wpisz nazwę");
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
            if (count($this->error) != 0) {
                return View("ajax.error_array")->with("error",$this->error);
            }
            else {
                $price = $Drugs->sumPrice(Input::get("dose"),Input::get("name"));
                $Drugs->addDrugs($Drugs->date,$price);
                return View("ajax.succes")->with("succes","Pomyslnie dodano");
            }
            
        }
        
        public function editRegistration() {
         $drugs = new Drugs;
         if ( (Auth::check()) ) {
             $listProduct = $drugs->selectProduct(Auth::User()->id);
             $info = $drugs->selectRegistration(Input::get("idDrugs"));
             $date = explode(" ",$info->date);
             return View("ajax.ChangeRegistration")->with("listProduct",$listProduct)
                     ->with("date1",$date[0])->with("date2",$date[1])->with("portion",$info->portion)
                     ->with("id",$info->id_products)->with("i",Input::get("i"))
                     ->with("idDrugs",Input::get("idDrugs"));
         }
       }
       
       public function updateShowRegistration() {
           $drugs = new Drugs;
           if ( (Auth::check()) ) {
               $drugs->selectRegistration2(Input::get("id"));
               $equivalent = $drugs->sumEquivalent($drugs->list);
               $benzo = $drugs->selectBenzo();
               $drugs->processPrice($drugs->list);
               return View("ajax.ShowUpdatesDrugs")->with("listDrugs",$drugs->list)
                       ->with("equivalent",$equivalent)->with("benzo",$benzo)->with("i",Input::get("i"));
           }
       }
       public function closeForm() {
           $drugs = new Drugs;
           if ( (Auth::check()) ) {
               $drugs->selectRegistration2(Input::get("id"));
               $equivalent = $drugs->sumEquivalent($drugs->list);
               $benzo = $drugs->selectBenzo(Auth::User()->id);
               $drugs->processPrice($drugs->list);
               return View("ajax.ShowUpdatesDrugs")->with("listDrugs",$drugs->list)
                       ->with("equivalent",$equivalent)->with("benzo",$benzo)->with("i",Input::get("i"));

           }
       }
        public function updateRegistration() {
            $Drugs = new Drugs;
            if ( (Auth::check()) ) {
                $date = $Drugs->checkDate(Input::get("date"),Input::get("time"));
                if (Input::get("nameProduct") == "") {
                    array_push($this->error, "Wpisz nazwę");
                }
                if ($date == -1 or $date == -2) {
                    array_push($this->error, "Błędna data");
                }
                if (Input::get("portion") == "") {
                    array_push($this->error, "Uzupełnij pole dawka");
                }
                else if (!is_numeric(Input::get("portion"))) {
                    array_push($this->error, "Pole dawka musi być numeryczne");
                }
                if ((Input::get("date") == "" or  Input::get("time") == "")) {
                    array_push($this->error, "Uzupełnij pole data i czas");
                }
                if (count($this->error) != 0) {
                    return View("ajax.error_array")->with("error",$this->error);
                }

                else {
                    $idProduct = $Drugs->selectIdProduct(Input::get("id"));
                    
                    $price = $Drugs->sumPrice(Input::get("portion"),$idProduct);
                    $price = $Drugs->sumPrice(Input::get("portion"),Input::get("nameProduct"));
                    $Drugs->editRegistration($Drugs->date,Input::get("id"),$price);
                    return View("ajax.succes")->with("succes","Pomyslnie dodano");
                     
                     
                }
            }
        }
     public function addDescriptionsAction() {
         $Drugs = new Drugs;
         if (Input::get("description") == "") {
             return View("ajax.error")->with("error","Musisz coś wpisać");
         }
         else {
             $Drugs->addDescription(Input::get("id_use"),date("Y-m-d H:i:s"));
             return View("ajax.succes")->with("succes","Pomyslnie dodano");
         }
         
     }

     public function deleteDrugs() {
         $Drugs = new Drugs;
         $bool = $Drugs->checkDrugs(Auth::User()->id,Input::get("id"));
         if ($bool == true) {
             $Drugs->deleteDescription(Input::get("id"));
             $Drugs->deleteDrugs(Input::get("id"),Auth::User()->id);
         }
     }
     public function sumAverage() {
         $Drugs = new Drugs;
         $Hash = new Hashs();
         $user = new user();
         if ( (Auth::check()) ) {
             $id = Auth::User()->id;
             $startDay = Auth::User()->start_day;
         }
         else if ($Hash->checkHashLogin() == true) {
             $user->updateHash();
             $id = $Hash->id;
             $startDay = $Hash->start;
         }
         else {
             return;
         }
            $bool = $Drugs->checkDrugs($id,Input::get("id"));
            if ($bool == true) {
                $list = $Drugs->returnIdProduct(Input::get("id"));
                $date = $Drugs->returnDateDrugs(Input::get("id"));
                $hourDrugs = $Drugs->sumAverage($list,$date,$Drugs->ifAlcohol,$id,$startDay);
                $array = array();
                for ($i=0;$i < count($hourDrugs);$i++) {
                   $array[$i] = $Drugs->sumDifferentDay($hourDrugs[$i][1],$hourDrugs[$i][2]);

                }
                return View("ajax.sum_average")->with("arrayDay",$array)->with("hourDrugs",$hourDrugs);
            }
         
     }
     public function sumAverage2() {
         $Drugs = new Drugs;
         $Hash = new Hashs();
         $user = new user();
         if ( (Auth::check()) ) {
             $id = Auth::User()->id;
             $startDay = Auth::User()->start_day;
         }
         else if ($Hash->checkHashLogin() == true) {
             $user->updateHash();
             $id = $Hash->id;
             $startDay = $Hash->start;
         }
         else {
             return;
         }
         $bool = $Drugs->checkDrugs($id,Input::get("id"));
         if ($bool == true) {
             $list = $Drugs->returnIdProduct(Input::get("id"));
             //$date = $Drugs->returnDateDrugs(Input::get("id"));
             
             $hourDrugs = $Drugs->sumAverage($list,Input::get("date1"),$Drugs->ifAlcohol,$id,$startDay,Input::get("date2"));
             
             $array = array();
             for ($i=0;$i < count($hourDrugs);$i++) {
                $array[$i] = $Drugs->sumDifferentDay($hourDrugs[$i][1],$hourDrugs[$i][2]);
                 
             }
             return View("ajax.sum_average")->with("arrayDay",$array)->with("hourDrugs",$hourDrugs);
              
              
         }
     }
     
     public function showDescriptionsAction() {
         $Drugs = new Drugs;
         $Hash = new Hashs();
         $user = new user();
         if ( (Auth::check()) ) {
             $id = Auth::User()->id;
             //$startDay = Auth::User()->start_day;
         }
         else if ($Hash->checkHashLogin() == true) {
             $user->updateHash();
             $id = $Hash->id;
             //$startDay = $Hash->start;
         }
         else {
             return;
         }
         $Drugs->description = $Drugs->selectDescription(Input::get("id"),$id);
         $Drugs->changeChar($Drugs->description);
         return View("ajax.show_description")->with("list",$Drugs->description);
         
     }
     public function sumBenzo() {
         $Drugs = new Drugs;
         $equivalent = $Drugs->selectEquivalent(Input::get("id"));

         $result = $Drugs->calculateEquivalent(Input::get("equivalent"),10,$equivalent);

         return View("ajax.equivalent_benzo")->with("result",$result);
     }
}