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
use Input;
use Auth;
use Hash;
use DB;
class HashController {
    public function updateHash() {
        $hashs = new Hashs;
        $hashs->updateHash(Auth::User()->id);
        return View("ajax.succes")->with("succes","Hash zmieniony pomyślnie");
        
    }
}
