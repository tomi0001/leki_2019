<?php


namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Services\calendar;
use App\Http\Services\User as user;
use Illuminate\Support\Facades\Input as Input;
use App\Http\Services\drugs as drugs;
use App\Http\Services\hashs as Hashs;
use Auth;
use App\Http\Services\search as Search;
use Hash;
use DB;
class SearchDrController {
    public function searchMain() {
        $Hash = new Hashs();
        if ( ($Hash->checkHashLogin() == true) ) {
            return View("Dr.search.Main");
        }
        
    }
    
    public function searchAction() {
        $Hash = new Hashs();
        if ( ($Hash->checkHashLogin() == true) ) {
            $search = new Search;
            $drugs = new drugs;
            $bool = $search->find($Hash->id);
            //$search->findNot();
            $error = "";
            if ($search->bool == false) {
                $list = $search->createQuestions($search->bool);
                if (count($list) == 0) {
                    $error = "Nic nie wyszukano";
                }
                $day = $search->changeArray($list);
                $drugs->selectColor($list);
                return View("Dr.search.searchAction")->with("listSearch",$list)->with("i",0)
                        ->with("day",$day)->with("inDay",Input::get("day"))
                        ->with("colorDrugs",$drugs->colorDrugs)->with("error",$error);
            }
            else if ($search->bool == true and $search->checkArrayFind() == false ) {
                return back()->with("error","Nic nie wyszukano")->withinput();
            }
            else if ( $search->bool == true ) {
                $list = $search->createQuestions($search->bool,$Hash->id);
                if (count($list) == 0) {
                    $error = "Nic nie wyszukano";
                }
                $day = $search->changeArray($list);
                $drugs->selectColor($list);
                //var_dump($search->id_product);
                return View("Dr.search.searchAction")->with("listSearch",$list)->with("i",0)
                        ->with("day",$day)->with("inDay",Input::get("day"))
                        ->with("colorDrugs",$drugs->colorDrugs)->with("error",$error);
            }
        }
        
    }
    public function selectDrugs() {
        $Hash = new Hashs();
        if ( ($Hash->checkHashLogin() == true) ) {
            $search = new Search;
            $drugs = new drugs;
            
            if (Input::get("dateStart") == "" or Input::get("dateEnd") == "") {
                return Redirect("/Produkt/Search")->with("errorSelect","Musisz uzupełnić daty");
            }
            
            $list = $search->selectDrugs(Input::get("dateStart"),Input::get("dateEnd"),$Hash->id);
            $drugs->selectColor($list);
            return View("Dr.search.selectDrugs")->with("listSearch",$list)
                    ->with("i",0)
                    ->with("colorDrugs",$drugs->colorDrugs);
;
            //$Drugs->returnIdProduct()
        }
    }
}
