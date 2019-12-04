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
use App\Http\Services\hashs as Hashs;
use Hash;
use DB;
class EditDrugsController
{
    public function EditDrugs() {
        if ( (Auth::check()) ) {
            $drugs = new Drugs;
            $hash = new Hashs;
            $listGroups = $drugs->selectGroup(Auth::User()->id);
            $listSubstance = $drugs->selectSubstance(Auth::User()->id);
            $listProduct = $drugs->selectProduct(Auth::User()->id);
            $selectHash = $hash->selectHash(Auth::User()->id);
            if (!isset($selectHash) ) {
                $selectHash = null;
            }
            return View("Drugs.EditDrugs")->with("listGroups",$listGroups)
                    ->with("start_day",Auth::User()->start_day)
                    ->with("listSubstance",$listSubstance)
                    ->with("listProduct",$listProduct)
                    ->with("hash",$selectHash);
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
            return View("ajax.EditSubstance")->with("list",$group)
                    ->with("id",Input::get("id"))->with("name",$name);
        }
        
    }
    public function EditProduct() {
        $drugs = new Drugs;
        if ( (Auth::check()) ) {
            $substance = $drugs->selectSubstanceName(Input::get("id"));
            $name = $drugs->selectNameProduct(Input::get("id"));
            return View("ajax.EditProduct")->with("list",$substance)
                    ->with("id",Input::get("id"))->with("name",$name);
        }
        
    }
    public function changeSubstance() {
        $drugs = new Drugs;
        if ( (Auth::check()) ) {
            $bool = $drugs->ifIdIsUsera("substances",Input::get("id_sub"));
            if ($bool == false) {
                return View("ajax.error")->with("error","Próbujesz zmodyfikować nie swoją substancję");
            }
            if (Input::get("name") == "") {
                return View("ajax.error")->with("error","Pole nazwa nie może być puste");
            }
            $bool = $drugs->checkName(Input::get("id_sub"),Input::get("name"),"substances");
            if ($bool == false) {
                return View("ajax.error")->with("error","Już jest substancja o takiej nazwie");
            }
            else {
                $drugs->updateSubstance(Input::get("id_sub"));
                return View("ajax.succes")->with("succes","Pomyslnie zmodyfikowana substancja");
            }
        }
    }
    public function changeProduct() {
        $drugs = new Drugs;
        if ( (Auth::check()) ) {
            $bool = $drugs->ifIdIsUsera("products",Input::get("id_sub"));
            if ($bool == false) {
                return View("ajax.error")->with("error","Próbujesz zmodyfikować nie swoją produkt");
            }
            if (Input::get("name") == "") {
                return View("ajax.error")->with("error","Pole nazwa nie może być puste");
            }
            $bool = $drugs->checkName(Input::get("id_sub"),Input::get("name"),"products");
            if ($bool == false) {
                return View("ajax.error")->with("error","Już jest produkt o takiej nazwie");
            }
            else {
                $drugs->updateProduct(Input::get("id_sub"));
                return View("ajax.succes")->with("succes","Pomyslnie zmodyfikowana produkt");
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