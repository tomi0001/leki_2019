<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Services;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Group as Group;
use App\Substance as Substances;
use App\Forwarding_substance as Forwarding_substance;
use App\Product as Product;
use App\Forwarding_group as Forwarding_group;
use App\Usee as usee;
use App\User as User;
use App\Hash as Hash;
use DB;
use App\Description as Description;
use App\Http\Services\calendar as calendar;
use App\Forwarding_description as Forwarding_description;
use Illuminate\Support\Facades\Input as Input;
use Auth;


class hashs {
    public function selectHash($id) {
        $Hash = new Hash;
        $select = $Hash->where("id_users",$id)->first();
        return $select;
    }
    public function updateHash($id) {
        //print Input::get("hash");
        $Hash = new Hash;
        $Hash->where("id_users",$id)->update(["if_true" => Input::get("if_true"),"hash" => Input::get("hash")]);
    }
    
    //put your code here
}
