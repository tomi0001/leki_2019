<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace rare\model;

/**
 * Description of stringsearch
 *
 * @author tomi5
 */
class stringsearch extends DB {
    public function search($city) {
        //$strings[0] = "Gr";
        //$strings[1] = "Grd";
        $string = " where like zvgportal_lage '%Gr%'";
        $st = DB::db()->prepare("select zvgportal_lage from zvgportal  where  zvgportal_lage like :lage  limit 50");
        //$query = "select zvgportal_lage from zvgportal $string ";
        $st->execute(["lage" => "%".$city."%"]);
        //$st = DB::exec($query);
        return $st->fetchAll();
    }
}
