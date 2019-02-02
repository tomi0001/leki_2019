<?php

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
use Illuminate\Support\Facades\Input as Input;
use Auth;

class drugs
{   
    public $date;
    public $start_day;
    public $color = array();
    public $list = array();
    public $color_how_product = array();
    public $i = 0;
    public $color_how_calendar;
    public function addGroup() :bool {
        //$name = str_replace("?", " ", Input::get("name"));
        if ($this->checkGroupName(Input::get("name"),Auth::User()->id) == "" ) {
            $Group = new Group;
            //$Group->addGroup(Input::get("name"),Input::get("color"),Auth::User()->id);
            $Group->name = Input::get("name");
            $Group->color = Input::get("color");
            $Group->id_users = Auth::User()->id;
            $Group->save();

            return true;
        }
        return false;
        
    }
    public function addDrugs($date,$price) {
        $use = new usee;
        //$use->portion = Input::get("portion");
        $use->id_users = Auth::User()->id;
        $use->id_products = Input::get("name");
        $use->date = $date;
        //$use->type_of_portion = Input::get("type");
        $use->price = $price;
        $use->portion = Input::get("dose");
        //$use->portion = Input::get("portion");
        $use->save();
        
        
    }
    public function showGroup(int $id_users)  {
        $Group = new Group;
        $list = $Group->where("id_users",$id_users)->get();
        return $list;
        
    }
    public function showSubstances(int $id_users)  {
        $Substance = new Substances;
        $list = $Substance->where("id_users",$id_users)->get();
        return $list;
        
    }
    public function checkIfHow($price,$how) :int {

        if (($price != "" and !is_numeric($price)) or ($how != "" and (!is_numeric($how) or strstr($how,".") ) )) {
            return -1;
        }
        if (($price == "" xor $how == "")) {
            return -2;
        }
        else {
            return 0;
        }
        
    }
    //Input::get("name"),Auth::User()->id,Input::get("percent"),Input::get("portion"),Input::get("price"),Input::get("how"))
    public function saveProduct($name,$id_users,$percent,$portion,$price,$how) {
        $Product = new Product;
        $Product->name = $name;
        $Product->id_users = $id_users;
        $Product->how_percent = $percent;
        $Product->type_of_portion = $portion;
        $Product->price = $price;
        $Product->how_much = $how;
        $Product->save();
        $id = $Product->where("id_users",$id_users)->orderBy("id","DESC")->first();
        return $id->id;
       }
    private function checkGroupName(string $name,int $id_users) {
        $Group = new Group;
        $check = $Group->where("name",$name)
                ->where("id_users",$id_users)->first();
        return $check;
        
    }
    public function checkGroupArray( $arrayGroup,int $id_users) :bool {
        $Group = new Group; 

        for ($i=0;$i < count($arrayGroup);$i++)  {
            $check = $Group->where("id_users",$id_users)
                    ->where("id",$arrayGroup[$i])->get();
            if ($check == "") {
                return false;
            }
        }
          //  return false;
        //}
        //else {
            return true;
        //}
        
    }
    public function checkSubstanceArray( $arraySubstance,int $id_users) :bool {
        $Substance = new Substances; 

        for ($i=0;$i < count($arraySubstance);$i++)  {
            $check = $Substance->where("id_users",$id_users)
                    ->where("id",$arraySubstance[$i])->get();
            if ($check == "") {
                return false;
            }
        }
          //  return false;
        //}
        //else {
            return true;
        //}
        
    }
    public function checkSubstances( $name,int $id_users) :bool {
         $Substance = new Substances;
         $check = $Substance->where("id_users",$id_users)
                    ->where("name",$name)->first();
         if ($check == "") {
             return true;
         }
         else {
             return false;
         }
        
        
    }
    public function checkProduct( $name,int $id_users) :bool {
         $Product = new Product;
         $check = $Product->where("id_users",$id_users)
                    ->where("name",$name)->first();
         if ($check == "") {
             return true;
         }
         else {
             return false;
         }
        
        
    }
    public function sumPrice($dose,$name) {
        $product = new product;
        $select = $product->where("id",$name)->first();
        if ($select->price  == "" and $select->how_much == "") {
            return 0;
        }
        else {
            return ($dose / $select->how_much) * $select->price;
        }
    }
    public function addSubstances( $arrayGroup, $equivalent, $name,int $id_users) {
        //$idLast = $this->addSubstancesname($name,$equivalent,$id_users);
        $Substances = new Substances;
        $Substances->name = $name;
        $Substances->id_users = $id_users;
        $Substances->equivalent = $equivalent;
        $Substances->save();
        
        $last_id = $Substances->orderby("id","DESC")->first();
        //return $last_id->id;
        //$idSubstances = $Substances->saveSubstance($name,$id_users,$equivalent);
        $this->addForwadingGroup($last_id->id,$arrayGroup);
        //var_dump($arrayGroup);
        
    }
    private function addForwadingGroup(int $idSubstances, $arrayGroup) {
        
        for ($i  =0;$i < count($arrayGroup);$i++) {
            $Forwading = new Forwarding_group;
            $Forwading->id_substances = (int)$idSubstances;
            $Forwading->id_groups = (int)$arrayGroup[$i];
            $Forwading->save();
            //print $idSubstances;
        }
        
    }
    public function checkDate($date,$time) {
        if ($time == "" and $date == "") {
            $this->date = date("Y-m-d H:i:s");
            return 0;
        }
        if ($time != "" and $date == "") {
            if ($this->ifHourIsGreaterNow($time) == false ) {
                $this->date = date("Y-m-d") . " " . $time;
                return -1;
            }
        }
        if ($time != "" and $date != "") {
            if ($this->ifHourIsGreaterNow($time,$date) == false ) {
                $this->date = $date . " " . $time;
                return -2;
            }
        }
        $this->date = $date . " " . $time;
        return 1;
    }
    private function ifHourIsGreaterNow($time,$date = "") {
        if ($date == "") {
            $date = date("Y-m-d");
        }
        $date2 = $date . " " . $time;
        $second = strtotime($date2);
        $second2 = strtotime(date("Y-m-d H:i:s"));
        if ($second < $second2) {
            return true;
        }
        else {
            return false;
        }
    }
    public function addForwadindSubstance(int $idProduct, $arraySubstance) {
        
        for ($i  =0;$i < count($arraySubstance);$i++) {
            $Forwading = new Forwarding_substance;
            $Forwading->id_products = (int)$idProduct;
            $Forwading->id_substances = (int)$arraySubstance[$i];
            $Forwading->save();
            //print $idSubstances;
        }
    }
    public function selectProduct(int $id_users) {
        $Product  = new Product;
        //$this->set_hour($id_users,$date);
        $list = $Product->where("id_users",$id_users)->get();
        return $list;
        
    }
    public function processPrice($listDrugs) {
        
        foreach ($listDrugs as $list) {
            $list->price = $this->calculatePrice($list->price);
            
        }
    }
    
    private function calculatePrice($price) {
        $gr = "";
        $zl = "";
        if (strstr($price,".")) {
            $div = explode(".",$price);
            //if ($div[0] == 0) {
                if (strlen($div[1]) == 1) {
                    $gr =  $div[1] . "0 Gr";
                }
                else if  (strlen($div[1] == 2) and $div[1][0] == 0) {
                    $gr =  $div[1][1] . " Gr";
                    
                }
                else {
                    $zl = $div[0] . " zł ";
                }
            //}
            if ($div[0] > 0) {
                $zl = $div[0] . " zł ";
            }
        }
        if (!strstr($price,".")) {
            $zl = $price . " zł ";
        }
        return $zl . $gr;
    }
    public function selectDrugs(int $id_users,$date) {
        $Drugs  = new usee;
        $this->set_hour($id_users,$date);
        $this->list = $Drugs->join("products","products.id","usees.id_products")
                ->where("usees.id_users",$id_users)
                ->where("usees.date",">=",$this->date)
                ->where("usees.date","<",$this->date_next)
                ->selectRaw("products.name as name")
                ->selectRaw("usees.price as price")
                ->selectRaw("usees.date as date")
                ->selectRaw("usees.portion as portion")
                ->selectRaw("usees.id_products as id")
                ->selectRaw("products.type_of_portion as type")
                ->orderBy("date")
                ->get();
        $this->color = $this->select_color($this->list);
        //var_dump($this->color_how_product);
        //$this->list = ;
        
    }
    /*
    private function check_if_product_hav_color($listDrugsId) {

        $forwarding_substances = new Forwarding_substance;
        $forwarding_group = new Forwarding_group;
        $group = new group;
        //foreach ($listDrugs as $list) {
          //$i = 0;
            //$pro = $forwarding_substances->where("id_products",$listDrugsId)->get();
            //foreach ($pro as $pro2) {
            //    $i = 0;
                
                $sub = $forwarding_substances->where("id_products",$listDrugsId)->get();
                foreach ($sub as $sub2) {
              //      $i = 0;
                    //print "kila";
                    $grup = $forwarding_group->where("id_substances",$sub2->id_substances)->get();
                    foreach ($grup as $grup2) {
                //        $i = 0;
                        $grup3 = $group->where("id",$grup2->id_groups)->get();
                        //$i = 0;
                        foreach ($grup3 as $grup4) {
                            //print "litt";
                              return true;

                        }
                    }
                }
                
            //}
            
            return false;
        
        
    }
     * */
     
    private function select_color($listDrugs) {
        $Product = new product;
        $forwarding_substances = new Forwarding_substance;
        $forwarding_group = new Forwarding_group;
        $group = new group;
        $color = array();
        $tmp = array();
        //$color2 = array();
        
        $j = 0;
        $i = 0;
        $z = 0;
        //print "dos";
        foreach ($listDrugs as $list) {
          //$i = 0;
            
            $pro = $Product->where("id",$list->id)->get();
            foreach ($pro as $pro2) {
            //    $i = 0;

               
                $sub = $forwarding_substances->where("id_products",$pro2->id)->get();
                foreach ($sub as $sub2) {
              //      $i = 0;
                    //print "kila";
                    $grup = $forwarding_group->where("id_substances",$sub2->id_substances)->get();
                    foreach ($grup as $grup2) {
                //        $i = 0;
                        $grup3 = $group->where("id",$grup2->id_groups)->get();
                        //$i = 0;
                        

                        foreach ($grup3 as $grup4) {
                            
                           // print $grup4->color;
                            //print "dol ";
                                                    $tmp[$z] = $grup4->color;
  
                             $z++;
                                //$color[$i] = 0;
                                //print "kupa ";
                                //break;
                             $color[$i] = $grup4->color;
                             
                             $i++;
                            }
                            
                            //var_dump($tmp);
                            //$bool  = $this->check_if_product_hav_color($list->id);
                                
                            //if ($bool == false) {
                                //print "lipa";
                              //  $this->color_how_product[$j] = 0;
                                //$this->color_how_product[$j] =  array_sum(array_unique($color));
                                //break;
                                //$j++;
                             
                            //}
                            //else {
                                //print ("<font color=green>$z</font>");
                                if (($z > 1)) {
                                    $this->color_how_product[$j] = array_product(array_unique($tmp)) + 10;
                                }
                                else {
                                    $this->color_how_product[$j] = $tmp[0];
                                }
                            //}
                            
                           
                        }
                        
                    }
                    
                }
                
   
                            
            //var_dump(array_unique($color));
            //print "<br>";
            //print $this->color_how_product[$j];// = max($color);
            $j++;
            $z = 0;
            $tmp = array();
            //$i = 0;
            }

        }
            //print "<font color=red>$j</font>";
            

            //print $color3 . "<br>";
            /*
            $i = 0;
            $list2 = $Product->join("forwarding_substances","forwarding_substances.id_products","products.id")
                        ->join("forwarding_groups","forwarding_groups.id_groups","forwarding_substances.id_substances")
                        ->join("groups","groups.id","forwarding_groups.id_groups")
                        ->selectRaw("groups.color as color")
                        ->where("products.id",$list->id)->get();
           //dd($list2->color);
            foreach ($list2 as $list3) {
                print "ss";
            }
            
            //if (empty($list3->color)) {
                $color[$i] = 0;
              //  print "tak";
            //}
            //else {
              //  $color[$i] = $list3->color;
            //}
            
            $i++;
        }
             * 
             */
        
        //return $color;
        
    
    private function set_hour(int $id_users,$date) {
        $user = new User;
        $hour = $user->where("id",$id_users)->first();
        $date_div = explode("-",$date);
        $second = mktime($hour->start_day,0,0,$date_div[1],$date_div[2],$date_div[0]);
        $second_next = $second + (24 * 3600);
        $date_next = date("Y-m-d H:i:s",$second_next);
        //$this->start_day = $hour->start_day;
        $this->date_next = $date_next;
        $this->date = $date;
        
    }
    
    //private function addSubstancesname($name,$equivalent,int $id_users) {
        
       
    //}
    //public function s() {
      //  $obiekt = new \App\Http\Middleware\calendar();
        
        //print $obiekt->g;
        
    //}
    
}
