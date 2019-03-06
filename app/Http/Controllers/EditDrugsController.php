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

use Illuminate\Support\Facades\Password as Password;
use Auth;
use App\Http\Services\drugs as Drugs;
use Hash;
use DB;
class EditDrugsController
{
    public function EditDrugs() {
        if ( (Auth::check()) ) {
            $drugs = new Drugs;
            //Password::sendResetLink(['id' => Auth::User()->id]);
            $listGroups = $drugs->selectGroup(Auth::User()->id);
            $listSubstance = $drugs->selectSubstance(Auth::User()->id);
            return View("Drugs.EditDrugs")->with("listGroups",$listGroups)
                    ->with("start_day",Auth::User()->start_day)
                    ->with("listSubstance",$listSubstance);
        }
        
    }
    public function EditGroup() {
        $drugs = new Drugs;
        if ( (Auth::check()) ) {
            $group = $drugs->selectGroupId(Input::get("id"));
            return View("ajax.EditGroup")->with("list",$group);
        }
    }
    public function EditSubstance() {
        $drugs = new Drugs;
        if ( (Auth::check()) ) {
            $group = $drugs->selectGroupName(Input::get("id"));
            $name = $drugs->selectNameSubstance(Input::get("id"));
            //print "lasdsf";
            return View("ajax.EditSubstance")->with("list",$group)
                    ->with("id",Input::get("id"))->with("name",$name);
        }
        
    }
    public function changeSubstance() {
        //var_dump(Input::get("id"));
        $drugs = new Drugs;
        if ( (Auth::check()) ) {
            //print Input::get("id_sub");
            $bool = $drugs->ifIdIsUsera("substances",Input::get("id_sub"));
            if ($bool == false) {
                return View("ajax.error")->with("error","Próbujesz zmodyfikować nie swoją grupę");
            }
            if (Input::get("name") == "") {
                return View("ajax.error")->with("error","Pole nazwa nie może być puste");
            }
            $bool = $drugs->checkName(Input::get("id_sub"),Input::get("name"),"substances");
            if ($bool == false) {
                return View("ajax.error")->with("error","Już jest grupa o takiej nazwie");
            }
            else {
                $drugs->updateSubstance(Input::get("id_sub"));
                //print (Input::get("id")[0]);
                return View("ajax.succes")->with("succes","Pomyslnie zmodyfikowana grupa");
            }
        }
    }
    public function changeGroup() {
        
        $drugs = new Drugs;
        if ( (Auth::check()) ) {
            $bool = $drugs->ifIdIsUsera("groups",Input::get("id"));
            if ($bool == false) {
                return View("ajax.error")->with("error","Próbujesz zmodyfikować nie swoją grupę");
            }
            if (Input::get("name") == "") {
                return View("ajax.error")->with("error","Pole nazwa nie może być puste");
            }
            $bool = $drugs->checkName(Input::get("id"),Input::get("name"),"groups");
            if ($bool == false) {
                return View("ajax.error")->with("error","Już jest grupa o takiej nazwie");
            }
            else {
                $drugs->updateName(Input::get("id"));
                return View("ajax.succes")->with("succes","Pomyslnie zmodyfikowana grupa");
            }
        }
        
    }
    
}