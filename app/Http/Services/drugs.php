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
use DB;
use App\Description as Description;
use App\Http\Services\calendar as calendar;
use App\Forwarding_description as Forwarding_description;
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
    public $date_next;
    public $dayMonth = array();
    public $listSum = array();
    public function addGroup() :bool {
        if ($this->checkGroupName(Input::get("name"),Auth::User()->id) == "" ) {
            $Group = new Group;
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
        $use->id_users = Auth::User()->id;
        $use->id_products = Input::get("name");
        $use->date = $date;
        $use->price = $price;
        $use->portion = Input::get("dose");
        $use->save();
        $id = $use->orderBy("id","DESC")->first();
        if (Input::get("description") != "") {
            $this->addDescription($id->id,$date);
        }
        
    }
    public function addDescription($idUse,$date) {
        $Description = new Description;
        $Description->date = $date;
        $Description->description = Input::get("description");
        $Description->id_users = Auth::User()->id;
        $Description->save();
        $id = $Description->orderBy("id","DESC")->first();
        $Forwarding_description = new Forwarding_description;
        $Forwarding_description->id_usees = $idUse;
        $Forwarding_description->id_descriptions = $id->id;
        $Forwarding_description->save();

        
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

            return true;

        
    }
    public function selectDescription($id) {
         
         
        $Description = new Forwarding_description;
        
        $list = $Description->join("descriptions","descriptions.id","forwarding_descriptions.id_descriptions")
                ->selectRaw("descriptions.description as description")
                ->selectRaw("descriptions.date as date")
                ->where("forwarding_descriptions.id_usees",$id)
                ->where("descriptions.id_users",Auth::User()->id)->get();
       
        return $list;
           
          
        
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

            return true;

        
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
    
    public function checkDrugs(int $id_users,$idDrugs) {
         $Use = new usee;
         $check = $Use->where("id_users",$id_users)
                    ->where("id",$idDrugs)->first();
         if ($check == "") {
             return false;
         }
         else {
             return true;
         }
    }
    public function returnDateDrugs($id ) {
       $Use = new usee;
       $date = $Use->where("id",$id)->first();
       return $date->date;
    }
    public function sumAverage($arrayId,$date) {
        
       $Use = new usee;
       $start = Auth::User()->start_day;

       $id_users = Auth::User()->id;
       $list = $Use->selectRaw("DATE(IF(HOUR(usees.date) >= '$start', DATE,Date_add(usees.date, INTERVAL - 1 DAY))) as DAT" )
                   ->selectRaw("SUM(usees.portion) AS portion")
                   ->selectRaw("usees.date as date")
                   ->wherein("usees.id_products",$arrayId)
                   ->where("usees.date","<=",$date)
                   ->where("usees.id_users",Auth::User()->id)
                   ->groupBy("DAT")
                   ->orderBy("DAT","DESC")->get();
        

       $tablica = array();
       $data1 = array();
       $czas = array();
       $dawka = array();
        $j = 0;
        $z = 0;
        $i = 0;
        foreach ($list as $rekord2) {
            $data1[$i] = explode(" ",$rekord2->date);
            $dawka[$i] = $rekord2->portion;
            $data = explode("-",$data1[$i][0]);
            $data2 = explode(":",$data1[$i][1]);
            $czas[$i] = mktime($data2[0],$data2[1],$data2[2],$data[1],$data[2],$data[0]);
            if ($i == 0) {
                $tablica[$j][0] = $dawka[$i];
                $tablica[$j][1] = $data1[$i][0];
                $tablica[$j][2] = $data1[$i][0];
                $tablica[$j][3] = 0;
              
            }
            elseif ($i != 0 and (($czas[$i-1]  - 146400) >  $czas[$i]))   {
                $tablica[$j][2] = $data1[$i-1][0];   
                $tablica[$j][3] = 1;
                $j++;               
                $tablica[$j][0] = $dawka[$i];
                $tablica[$j][1] = $data1[$i][0];
                $tablica[$j][2] = $data1[$i][0];
                $tablica[$j][3] = 0;
                
                //break;
            }
            elseif ($i != 0 and $dawka[$i] != $dawka[$i-1]) {
                $tablica[$j][2] = $data1[$i-1][0];
                $j++;
                $tablica[$j][0] = $dawka[$i];
                $tablica[$j][1] = $data1[$i][0];
                $tablica[$j][2] = $data1[$i][0];
                $tablica[$j][3] = 0;
                
                
            }
            elseif ($i == count($list)-1) {
                $tablica[$j][0] = $dawka[$i];
                $tablica[$j][2] = $data1[$i][0];
                
                $tablica[$j][3] = 0;
        
            }
            
        
            $i++;
        }
         
       return $tablica;
       
    }
    
    
    public function sumDifferentDay($date1,$date2) {
        
        $date11 = StrToTime($date1);
        $date22 = StrToTime($date2);
        $result = $date11  - $date22;
        return (int)($result  / 3600 / 24) + 1;
        
        
    }
    
    public function returnIdProduct($id) {
        $Use = new usee;
        $forwarding_substances = new Forwarding_substance;
        $listIdSub = array();
        $selectIdProduct = $Use->where("id",$id)->first();
        $selectIdSub = $forwarding_substances
                ->where("id_products",$selectIdProduct->id_products)->get();
        $i = 0;
        foreach ($selectIdSub as $selectIdSub2) {
               $listIdSub[$i] = $selectIdSub2->id_substances;
               $i++; 
        }
         $selectIdSub3 = $forwarding_substances
                            ->orwherein("id_substances",$listIdSub)
                            ->groupBy("id_products")
                            ->havingRaw("count(*) = $i")->get();
         $array = array();
         $i = 0;
         foreach ($selectIdSub3 as $selectIdSub4) {
             $array[$i] = $selectIdSub4->id_products;
             $i++;
         }
         if ($i == 0) {
             return array($selectIdProduct->id_products);
         }
         return $array;
                
    }
     public function deleteDescription($idDrugs) {
         $Description = new Forwarding_description;
         $Description->where("id_usees",$idDrugs)->delete();
     }
    
    public function deleteDrugs($idDrugs,$id_users) {
        $Use = new usee;
        $Use->where("id_users",$id_users)
                ->where("id",$idDrugs)->delete();
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
        if (($select->price  == "" and $select->how_much == "") or $select->how_much == 0) {
            return 0;
        }
        else {
            return ($dose / $select->how_much) * $select->price;
        }
    }
    public function addSubstances( $arrayGroup, $equivalent, $name,int $id_users) {
        $Substances = new Substances;
        $Substances->name = $name;
        $Substances->id_users = $id_users;
        $Substances->equivalent = $equivalent;
        $Substances->save();
        
        $last_id = $Substances->orderby("id","DESC")->first();
        $this->addForwadingGroup($last_id->id,$arrayGroup);
        
    }
    private function addForwadingGroup(int $idSubstances, $arrayGroup) {
        
        for ($i  =0;$i < count($arrayGroup);$i++) {
            $Forwading = new Forwarding_group;
            $Forwading->id_substances = (int)$idSubstances;
            $Forwading->id_groups = (int)$arrayGroup[$i];
            $Forwading->save();
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
        }
    }
    public function selectProduct(int $id_users) {
        $Product  = new Product;
        $list = $Product->where("id_users",$id_users)->get();
        return $list;
        
    }
    public function processPrice($listDrugs) {
        
        foreach ($listDrugs as $list) {
            $list->price = $this->calculatePrice($list->price);
            
        }
    }
    public function checkIfDescription($DrugsList) {
        $idDescription = array();
        $i = 0;
        $Forwarding_description = new Forwarding_description;
        foreach ($DrugsList as $list) {
            $id = $Forwarding_description->where("id_usees",$list->idDrugs)->count();
            if ($id > 0) {
                $idDescription[$i] = true;
            }
            else {
                $idDescription[$i] = false;
            }
            $i++;
        }
        return $idDescription;
    }
    private function calculatePrice($price) {
        $gr = "";
        $zl = "";
        if (strstr($price,".")) {
            $div = explode(".",$price);
                if (strlen($div[1]) == 1) {
                    $gr =  $div[1] . "0 Gr";
                }
                else if  (strlen($div[1] == 2) and $div[1][0] == 0) {
                    $gr =  $div[1][1] . " Gr";
                    
                }
                else {
                    $zl = $div[0] . " zł ";
                }
            if ($div[0] > 0) {
                $zl = $div[0] . " zł ";
            }
        }
        if (!strstr($price,".")) {
            $zl = $price . " zł ";
        }
        return $zl . $gr;
    }
    public function selectDrugsMonth($year,$month) {
        $calendar = new calendar;
        $howmonth = $calendar->check_month($month,$year);
        for ($i = 0;$i < $howmonth;$i++) {
            $j = $i + 1;
            $this->selectDrugs(Auth::User()->id,$year . "-" . $month . "-" . $j);
            $this->dayMonth[$i] = $this->selectColor($this->list);
            
        }
        
    }
    public function showSumDrugs(int $id_users,$date) {
        $Drugs  = new usee;
        $this->listSum = $Drugs->join("products","products.id","usees.id_products")
                                ->selectRaw("sum(usees.portion) as portion ")
                                ->selectRaw(" products.name as name ")
                                ->selectRaw("products.type_of_portion as type")
                            ->where("usees.date",">=",$this->date)
                            ->where("usees.date","<",$this->date_next)
                            ->where("usees.id_users",$id_users)
                            ->groupBy("id_products")->get();
    }
    
    public function sumPercentAlkohol() {
        $sum = 0;
        foreach ($this->list as $list) {
            if ($list->percent == null) {
                $list->percent = 0;
            }
            else {
                $list->percent = $this->sumAlkohol($list->portion,$list->percent);
                $sum += $list->percent;
            }
        }
        return $sum;
    }
    
    public function sumAllEquivalent($equivalent) {
        $sum = 0;
        for ($i=0;$i < count($equivalent);$i++) {
            $sum += $equivalent[$i];
        }
        return $sum;
    }
    
    public function sumEquivalent($listDrugs) {
        $forwarding_substances = new Forwarding_substance;
        $equivalent = array();
        $i = 0;
        foreach ($listDrugs as $list) {
            $tmp = $forwarding_substances->join("substances","substances.id","forwarding_substances.id_substances")
                    ->join("usees","usees.id_products","forwarding_substances.id_products")
                    ->selectRaw("substances.equivalent as equivalent")
                    ->selectRaw("usees.date as date")
                    ->selectRaw("usees.portion as portion")
                    ->where("forwarding_substances.id_products",$list->id)
                    ->where("usees.id",$list->idDrugs)->get();
            foreach ($tmp as $tmp2) {}
               if (isset($tmp2) and $tmp2->equivalent != 0) {
                   
                $equivalent[$i] = $this->calculateEquivalent($tmp2->portion, $tmp2->equivalent, 10);
               }
               else {
                   $equivalent[$i] = 0;
               }

            $i++;
        }
        return $equivalent;
    }
    public function calculateEquivalent($portion,$equivalent,$diazepam) {
        return round(($portion / $equivalent) * $diazepam,2);
    }
    public function selectPortion($id) {
        $usee = new Usee;
        $portion = $usee->find($id);
        return $portion;
    }
    public function selectBenzo() {
        $substances = new Substances;
        $list = $substances->where("id_users",Auth::User()->id)
                           ->where("equivalent","!=",0)
                           ->where("equivalent","!=",null)->get();
        return $list;
        
    }
    private function sumAlkohol($portion,$percent) {
        return  ($portion * $percent) / 100;
        
    }
    
    public function selectDrugs(int $id_users,$date) {
        $Drugs  = new usee;
        $this->set_hour($id_users,$date);
        $this->list = $Drugs->join("products","products.id","usees.id_products")
                ->where("usees.id_users",$id_users)
                ->where("usees.date",">=",$this->date)
                ->where("usees.date","<",$this->date_next)
                ->selectRaw("products.name as name")
                ->selectRaw("products.how_percent as percent")
                ->selectRaw("usees.price as price")
                ->selectRaw("usees.date as date")
                ->selectRaw("usees.portion as portion")
                ->selectRaw("usees.id_products as id")
                ->selectRaw("usees.id as idDrugs")
                ->selectRaw("products.type_of_portion as type")
                ->orderBy("date")
                ->get();

        
    }
    public function selectEquivalent($id) {
        $substances = new Substances;
        $equivalent = $substances->find($id);
        return $equivalent->equivalent;
        
    }
  
     public function selectColor($drugsList) {
        $Product = new product;
        $forwarding_substances = new Forwarding_substance;
        $forwarding_group = new Forwarding_group;
        $group = new group;
        $colorarray = array();
        $bool = false;
        if (count($drugsList) == 0) {
            return -1;
        }
        $color3 = "";
           foreach ($drugsList as $list) {
           
            $idSub = $forwarding_substances->where("id_products",$list->id)->get();
            foreach ($idSub as $idSubstances) {
                $idGru = $forwarding_group->where("id_substances",$idSubstances->id_substances)->get();
                foreach ($idGru as $idgroup) {
                    $color = $group->where("id",$idgroup->id_groups)->get();
                    foreach ($color as $color2) {
                            if ($color2->color == null or $color2->color == 0){
                                continue;
                            }
                            $colorarray[] = (int) $color2->color;

                    }
                }
            }
            $bool = true;
            
           }

           if (empty($colorarray)) {
               return 0;
           }
           if ($bool == true) {
               return $this->colorForDay((array_product(array_unique($colorarray))));
           }

         
     }

    private function colorForDay(int $color) {

        
        if ($color == "") {
            return 0;
        }
        else if ($color == 3) {
            return 2;
        }
        else if ($color == 4) {
            return 3;
        }
        else if ($color == 5) {
            return 4;
        }
        else if ($color > 7 and $color < 13) {
            return 5;
        }
        else if ($color > 14 and $color < 16) {
            return 6;
        }
        else if ($color >= 16 and $color < 21) {
            return 7;
        }
        else if ($color >= 21 and $color < 61) {
            return 8;
        }
        else {
            return 0;
        }
    }
 
    private function set_color(int $colorInt) :int {
            if ($colorInt == 1) {
                return 1;
            }
            else if ($colorInt == 2) {
                return 2;
            }
            else if ($colorInt == 3) {
                return 3;
            }
            else if ($colorInt == 4) {
                return 4;
            }
            else if ($colorInt == 5) {
                return 5;
            }
            else if ($colorInt > 5 and $colorInt < 20) {
                return 6;
            }
            else {
                return 0;
            }
        }
 
    
    private function set_hour(int $id_users,$date) {
        $user = new User;
        
        $hour = $user->where("id",$id_users)->first();
        $date_div = explode("-",$date);
        $second = mktime($hour->start_day,0,0,$date_div[1],$date_div[2],$date_div[0]);
        $second_next = $second + (24 * 3600);
        $date_next = date("Y-m-d H:i:s",$second_next);
        $date_back = date("Y-m-d H:i:s",$second);
        $this->date_next = $date_next;
        $this->date = $date_back;
        
    }
    
    
}
