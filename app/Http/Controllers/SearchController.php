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
use Auth;
use App\Http\Services\search as Search;
use Hash;
use DB;
class SearchController {   
    public function searchMain() {
        if ( (Auth::check()) ) {
            return View("search.Main");
        }
        
    }
    public function searchAction() {
        if ( (Auth::check()) ) {
            $search = new Search;
            $drugs = new drugs;
            $bool = $search->find();
            $error = "";
            if ($bool == false) {
                $list = $search->createQuestions($bool);
                if (count($list) == 0) {
                    $error = "Nic nie wyszukano";
                }
                $day = $search->changeArray($list);
                $drugs->selectColor($list);
                return View("search.searchAction")->with("listSearch",$list)->with("i",0)
                        ->with("day",$day)->with("inDay",Input::get("day"))
                        ->with("colorDrugs",$drugs->colorDrugs)->with("error",$error);
            }
            else if ($bool == true and $search->checkArrayFind() == false) {
                return back()->with("error","Nic nie wyszukano")->withinput();
            }
            else if ( $bool == true) {
                $list = $search->createQuestions($bool);
                if (count($list) == 0) {
                    $error = "Nic nie wyszukano";
                }
                $day = $search->changeArray($list);
                $drugs->selectColor($list);
                return View("search.searchAction")->with("listSearch",$list)->with("i",0)
                        ->with("day",$day)->with("inDay",Input::get("day"))
                        ->with("colorDrugs",$drugs->colorDrugs)->with("error",$error);
            }
        }
        
    }
    
    
    
}