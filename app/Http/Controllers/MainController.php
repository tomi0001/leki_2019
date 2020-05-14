<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Services\calendar;
use App\Http\Services\User as user;
use App\Product as product;
use App\Http\Services\drugs as Drugs;
use App\Http\Services\hashs as Hashs;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Input;
use Auth;
use Hash;
use DB;
use Cookie;
class MainController 
{
    
    public function Main($year = "",$month = "",$day = "",$action = "") {
        $user = new user();
        //$Hash = new Hashs();s
        //print $_COOKIE['hash'];
        //var_dump($_COOKIE);
     //$value = $request->cookie('hash');
      //echo $value;
        $product = new product;
        
        if ( (Auth::check()) ) {
            $kalendar = new calendar();
            $Drugs = new Drugs;
            $kalendar->set_date($month,$action,$day,$year);
            $how_day_month = $kalendar->check_month($kalendar->month,$kalendar->year);
            $back_month = $kalendar->return_back_month($kalendar->month,$kalendar->year);
            $next_month = $kalendar->return_next_month($kalendar->month,$kalendar->year);
            $text_month = $kalendar->return_month_text($kalendar->month);
            $next_year  = $kalendar->return_next_year($kalendar->year);
            $back_year  = $kalendar->return_back_year($kalendar->year);
            $listProduct = $Drugs->selectProduct(Auth::User()->id);
            $Drugs->selectDrugsMonth($kalendar->year,$kalendar->month,Auth::User()->id);
            $Drugs->selectDrugs(Auth::User()->id,$kalendar->year . "-" . $kalendar->month . "-" . $kalendar->day);
            $Drugs->showSumDrugs(Auth::User()->id,$kalendar->year . "-" . $kalendar->month . "-" . $kalendar->day);
            $sumAlkohol = $Drugs->sumPercentAlkohol();
            $separate = $Drugs->separateDrugs();
            $Drugs->processPrice($Drugs->list);
            $equivalent = $Drugs->sumEquivalent($Drugs->list);
            $allEquivalent = $Drugs->sumAllEquivalent($equivalent);
            $benzo = $Drugs->selectBenzo(Auth::User()->id);
            $Drugs->selectColor($Drugs->list);
            $i = count($Drugs->list);
            $ifDescription = $Drugs->checkIfDescription($Drugs->list);

            return view("Main.main")
                    ->with("month",$kalendar->month)
                    ->with("year",$kalendar->year)
                    ->with("day",$kalendar->day)
                    ->with("action",$kalendar->action)
                    ->with("how_day_month",$how_day_month)
                    ->with("back",$back_month)
                    ->with("next",$next_month)
                    ->with("back_year",$back_year)
                    ->with("next_year",$next_year)
                    ->with("text_month",$text_month)
                    ->with("day2",1)
                    ->with("day1",1)
                    ->with("day3",$kalendar->day)
                    ->with("day_week",$kalendar->day_week)
                    ->with("list_product",$listProduct)
                    ->with("listDrugs",$Drugs->list)
                    ->with("ifDescription",$ifDescription)
                    ->with("j",$i)
                    ->with("color",$Drugs->dayMonth)
                    ->with("listSum",$Drugs->listSum)
                    ->with("sumAlkohol",$sumAlkohol)
                    ->with("equivalent",$equivalent)
                    ->with("allEquivalent",$allEquivalent)
                    ->with("benzo",$benzo)
                    ->with("colorDrugs",$Drugs->colorDrugs)
                    ->with("separate",$separate)
                    ->with("date",$kalendar->year . "-" . $kalendar->month . "-" . $kalendar->day . "?");
           
        }
        else {
            
            return Redirect('/User/Login');
        }
        
         
    }
    
}

    