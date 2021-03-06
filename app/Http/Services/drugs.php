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
    public $colorDrugs = array();
    public $listSum = array();
    public $description = array();
    public $ifAlcohol = false;
    public $countProduct = 0;
    public $sumDayAverage = 0;
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
    public function selectRegistration(int $id) {
         $use = new usee;
         $select = $use->where("id",$id)->where("id_users",Auth::User()->id)->first();
         return $select;
    }
    public function selectRegistration2(int $id) {
         $use = new usee;
         $this->list = $use->join("products","products.id","usees.id_products")
                 ->where("usees.id",$id)
                 ->where("usees.id_users",Auth::User()->id)
                 ->selectRaw("products.name as name")
                ->selectRaw("products.how_percent as percent")
                ->selectRaw("usees.price as price")
                ->selectRaw("usees.date as date")
                ->selectRaw("usees.portion as portion")
                ->selectRaw("usees.id_products as id")
                ->selectRaw("usees.id as idDrugs")
                ->selectRaw("products.type_of_portion as type")
                 ->get();

    }
    public function checkName(int $id,string $name,string $table) {
        
        $gro = DB::table($table)->where("id","!=",$id)->where("name",$name)->first();
        if (!empty($gro)) {
            return false;
        }
        else {
            return true;
        }
    }
    
    private function selectIdGroup(int $id) {
        $forwarding = new Forwarding_group;
        $list = $forwarding->where("id_substances",$id)->get();
        return $list;
        
    }
    private function selectIdSubstance(int $id) {
        $forwarding = new Forwarding_substance;
        $list = $forwarding->where("id_products",$id)->get();
        return $list;
        
    }
    public function selectIdProduct(int $id) {
        $Use = new usee;
        $list = $Use->where("id",$id)->first();
        return $list->id_products;
        
    }
    public function selectGroupName(int $id) {
        $group = new Group;
        $arrayGroup = array();
        $idGroup = $this->selectIdGroup($id);
        $i = 0;

        $list = $group->selectRaw("groups.name as name")
                ->selectRaw("groups.id as id_gro")
                ->where("groups.id_users",Auth::User()->id)
                ->orderBy("name")
                ->get();
        foreach ($list as $listGroup)  {
            $bool = false;
            
            foreach ($idGroup as $id_Gro) {
                if ($listGroup->id_gro == $id_Gro->id_groups) {
                    $bool = true;
                }
                
            }
            
            if ($bool == true) {
                $arrayGroup[$i][0] = $listGroup->id_gro;
                $arrayGroup[$i][1] = $listGroup->name;
                $arrayGroup[$i][2] = true;
            }
            else {
                $arrayGroup[$i][0] = $listGroup->id_gro;
                $arrayGroup[$i][1] = $listGroup->name;
                $arrayGroup[$i][2] = false;
            }
            $i++;
        }
        return $arrayGroup;
        
    }
    
   public function selectSubstanceName(int $id) {
        $group = new Substances;
        $arrayGroup = array();
        $idGroup = $this->selectIdSubstance($id);
        $i = 0;

        $list = $group->selectRaw("substances.name as name")
                    ->selectRaw("substances.id as id_sub")
                ->where("substances.id_users",Auth::User()->id)
                ->orderBy("name")
                ->get();
        foreach ($list as $listGroup)  {
            $bool = false;
            
            foreach ($idGroup as $id_Gro) {
                if ($listGroup->id_sub == $id_Gro->id_substances) {
                    $bool = true;
                }
                
            }
            
            if ($bool == true) {
                $arrayGroup[$i][0] = $listGroup->id_sub;
                $arrayGroup[$i][1] = $listGroup->name;
                $arrayGroup[$i][2] = true;
            }
            else {
                $arrayGroup[$i][0] = $listGroup->id_sub;
                $arrayGroup[$i][1] = $listGroup->name;
                $arrayGroup[$i][2] = false;
            }
            $i++;
        }
        return $arrayGroup;
        
    }
    public function selectGroupId(int $id) {
        $group = new Group;
        $list = $group->where("id",$id)->first();
        return $list;
    }
    public function updateName(int $id) {
        $group = new Group;
        $group->where("id",$id)->update(["name" => Input::get("name"),"color"=> Input::get("color")]);
    }
    public function updateSubstance(int $id) {
        $Forwarding_group = new Forwarding_group;
        $Forwarding_group->where("id_substances",$id)->delete();
        $this->addForwardingSubstance($id);
        $this->updateName2($id,"substances");
    }
    public function updateProduct(int $id) {
        $Forwarding_group = new Forwarding_substance;
        $Forwarding_group->where("id_products",$id)->delete();
        $this->addForwardingProduct($id);
        $this->updateName2($id,"products");
    }
    private function updateName2(int $id,string $table) {
        DB::table($table)->where("id",$id)->update(["name"=>Input::get("name")]);
        
    }
    private function addForwardingSubstance(int $id) {
        for ($i = 0;$i < count(Input::get("id"));$i++) {
            $Forwarding_group = new Forwarding_group;
            $Forwarding_group->id_substances = $id;
            $Forwarding_group->id_groups = Input::get("id")[$i];
            $Forwarding_group->save();
        }
        
    }
    private function addForwardingProduct(int $id) {
        for ($i = 0;$i < count(Input::get("id"));$i++) {
            $Forwarding_group = new Forwarding_substance;
            $Forwarding_group->id_products = $id;
            $Forwarding_group->id_substances = Input::get("id")[$i];
            $Forwarding_group->save();
        }
        
    }
    public function charset_utf_fix2($string) {
 
	$utf = array(
	  "Ą" =>"%u0104",
	  "Ć" => "%u0106",
	  "Ę"  => "%u0118",
	  "Ł" => "%u0141",
	  "Ń" => "%u0143",
	  "Ó" => "%D3",
	  "Ś" => "%u015A",
	  "Ź" => "%u0179",
	  "Ż" => "%u017B",
	  "ą" => "%u0105",
	  "ć" => "%u0107",
	  "ę" => "%u0119",
	  "ł" => "%u0142",
	  "ń" => "%u0144",
	  "ó" => "%F3",
	  "ś" => "%u015B",
	  "ź" => "%u017A",
	  "ż" => "%u017C",
          " " => "&nbsp"
	);
	
	return str_replace(array_keys($utf), array_values($utf), $string);
        
	
    }
    public function charset_utf_fix($string) {
 
	$utf = array(
	 "%u0104" => "Ą",
	 "%u0106" => "Ć",
	 "%u0118" => "Ę",
	 "%u0141" => "Ł",
	 "%u0143" => "Ń",
	 "%D3" => "Ó",
	 "%u015A" => "Ś",
	 "%u0179" => "Ź",
	 "%u017B" => "Ż",
	 "%u0105" => "ą",
	 "%u0107" => "ć",
	 "%u0119" => "ę",
	 "%u0142" => "ł",
	 "%u0144" => "ń",
	 "%F3" => "ó",
	 "%u015B" => "ś",
	 "%u017A" => "ź",
	 "%u017C" => "ż",
         "%20" => " ",
            "&nbsp" => " "
	);
	
	return str_replace(array_keys($utf), array_values($utf), $string);
        
	
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
    public function selectDescription($id,$idUsers) {
         
         
        $Description = new Forwarding_description;
        
        $list = $Description->join("descriptions","descriptions.id","forwarding_descriptions.id_descriptions")
                ->selectRaw("descriptions.description as description")
                ->selectRaw("descriptions.date as date")
                ->where("forwarding_descriptions.id_usees",$id)
                ->where("descriptions.id_users",$idUsers)->get();
       
        return $list;
           
          
        
    }
    public function changeChar($list) {
        
        foreach ($list as $description) {
            $description->description = $this->charset_utf_fix($description->description);
        }
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
    
    public function separateDrugs() {
        $array = [];
        $i = 0;
        foreach ($this->list as $list) {
            $array[$i]["second"] = strtotime($list->date);
            $array[$i]["bool"] = false;
            if ($i != 0 and $array[$i-1]["second"] < $array[$i]["second"] - 140) {
                $array[$i-1]["bool"] = true;
                
            }
          
            $i++;
        }
        return $array;
    }
    /*
    public function sumAverageCount($arrayId,$date,$ifAlcohol,$id,$startDay,$date2 = "") {
          
       $Use = new usee;
       $start = $startDay;
       $listen = usee::query();
       
       $id_users = $id;
       //if ($ifAlcohol == true) {
              
                    $listen->join("products","products.id","usees.id_products");
        //}
        $listen->selectRaw("DATE(IF(HOUR(usees.date) >= '$start', DATE,Date_add(usees.date, INTERVAL - 1 DAY))) as DAT" );
        $listen->selectRaw("products.type_of_portion as type" );
                if ($this->ifAlcohol == true) {
                    
              
                    $listen->selectRaw("round(SUM((usees.portion * products.how_percent / 100)),2) AS portion");
                }
                else {
                    
                   $listen->selectRaw("SUM(usees.portion) AS portion");
                }
                   $listen->selectRaw("usees.date as date")
                   ->wherein("usees.id_products",$arrayId);
        if ($date2 == "") {
                   $listen->where("usees.date","<=",$date);
        }
        else {
            $listen->where("usees.date",">=",$date)
                    ->where("usees.date","<=",$date2);
        }
                   $listen->where("usees.id_users",$id_users)
                   ->groupBy("DAT")
                   //->havingRaw("")
                   ->orderBy("DAT","DESC");
                
                $list = $listen->get();
        
       //print count($list);
       $array = array();
       $data1 = array();
       $time = array();
       $dose = array();
        $j = 0;
        $z = 0;
        $i = 0;
        $type = "";
        foreach ($list as $rekord2) {
            switch ($rekord2->type) {
                case '3': $type = " ilości";
                    break;
                case '2': $type = " mililitry";
                    break;
                default: $type = " mg";
                    break;
            
            }
            $data1[$i] = explode(" ",$rekord2->date);
            $dose[$i] = $rekord2->portion;
            $data = explode("-",$data1[$i][0]);
            $data2 = explode(":",$data1[$i][1]);
            $daySum = 0;
            $time[$i] = mktime($data2[0],$data2[1],$data2[2],$data[1],$data[2],$data[0]);
            if ($i == 0) {
                $daySum++;
              
            }
            elseif ($i != 0 and (($time[$i-1]  - 146400) >  $time[$i]))   {
               $daySum++;
                
                //break;
            }
            elseif ($i != 0 and $dose[$i] != $dose[$i-1]) {
                $daySum++;
                
                
            }
            elseif ($i == count($list)-1) {
               $daySum++;
        
            }
            
        
            $i++;
        }
         
       return $daySum;
    }
     * 
     */
    public function sumAverage($arrayId,$date,$ifAlcohol,$id,$startDay,$date2 = "") {
        
       $Use = new usee;
       $start = $startDay;
       $listen = usee::query();

       $id_users = $id;
       //if ($ifAlcohol == true) {
              
                    $listen->join("products","products.id","usees.id_products");
        //}


        $listen->selectRaw("DATE(IF(HOUR(usees.date) >= '$start', DATE,Date_add(usees.date, INTERVAL - 1 DAY))) as DAT" );
        $listen->selectRaw("products.type_of_portion as type" );

                if ($this->ifAlcohol == true) {
                    
              
                    $listen->selectRaw("round(SUM((usees.portion * products.how_percent / 100)),2) AS portion");
                }
                else {
                    
                   $listen->selectRaw("SUM(usees.portion) AS portion");
                    $listen->selectRaw("count(usees.portion) AS count");
                }
                   $listen->selectRaw("usees.date as date")
                   ->wherein("usees.id_products",$arrayId);


        if ($date2 == "") {
                   $listen->where("usees.date","<=",$date);

        }
        else {
            $listen->where("usees.date",">=",$date)
                    ->where("usees.date","<=",$date2);

        }
                   $listen->where("usees.id_users",$id_users)
                   ->groupBy("DAT")
                   //->havingRaw("")
                   ->orderBy("DAT","DESC");


                
                $list = $listen->get();

        
       //print count($list);
       $array = array();
       $data1 = array();
       $time = array();
       $dose = array();
       $count = array();
        $j = 0;
        $z = 0;
        $i = 0;
        $x = 0;
        $type = "";
        foreach ($list as $rekord2) {
            switch ($rekord2->type) {
                case '3': $type = " ilości";
                    break;
                case '2': $type = " mililitry";
                    break;
                default: $type = " mg";
                    break;
            
            }
            //print $rekord2->DAT . "<br>";
            $data1[$i] = explode(" ",$rekord2->date . " $start:00:00");
            $dose[$i] = $rekord2->portion;
            $count[$i] = $rekord2->count;
            $data = explode("-",$data1[$i][0]);
            $data2 = explode(":",$data1[$i][1]);
            $time[$i] = mktime($data2[0],$data2[1],$data2[2],$data[1],$data[2],$data[0]);
            if ($i == 0) {
                $array[$j][0] = $dose[$i] . $type;
                $array[$j][1] = $data1[$i][0];
                $array[$j][2] = $data1[$i][0];
                $array[$j][3] = 0;
                $array[$j][4] = $count[$i];
              
              
            }
            elseif ($i != 0 and (($time[$i-1]  - 146400) >  $time[$i]))   {
                $array[$j][2] = $data1[$i-1][0];   
                $array[$j][3] = 1;
                $j++;               
                $array[$j][0] = $dose[$i] . $type;
                $array[$j][1] = $data1[$i][0];
                $array[$j][2] = $data1[$i][0];
                $array[$j][3] = 0;
                $array[$j][4] = $count[$i];
                //$x--;
                //break;
            }
            elseif ($i != 0 and $dose[$i] != $dose[$i-1]) {
                $array[$j][2] = $data1[$i-1][0];
                $j++;
                $array[$j][0] = $dose[$i] . $type;
                $array[$j][1] = $data1[$i][0];
                $array[$j][2] = $data1[$i][0];
                $array[$j][3] = 0;
                $array[$j][4] = $count[$i];
                
             
                
                
            }
            else if ($i != 0 and $count[$i] != $count[$i-1]) {
                $array[$j][2] = $data1[$i-1][0];
                $j++;
                $array[$j][0] = $dose[$i] . $type;
                $array[$j][1] = $data1[$i][0];
                $array[$j][2] = $data1[$i][0];
                $array[$j][3] = 0;
                $array[$j][4] = $count[$i];
                 
            }
            elseif ($i == count($list)-1) {
                $array[$j][0] = $dose[$i] . $type;
                $array[$j][2] = $data1[$i][0];
                
                $array[$j][3] = 0;
               
        
            }
            
        
            $i++;
            $x++;
        }
           $this->sumDayAverage = $x;
       return $array;
       
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
        $selectIdSub1 = $forwarding_substances->selectRaw("forwarding_substances.id_substances as id_substances")
                ->selectRaw("products.type_of_portion as type_of_portion")
                ->join("products","products.id","forwarding_substances.id_products")
                ->where("forwarding_substances.id_products",$selectIdProduct->id_products)->get();
        $i = 0;
        foreach ($selectIdSub1 as $selectIdSub2) {
               $listIdSub[$i] = $selectIdSub2->id_substances;
               //print "d";
               if ($selectIdSub2->type_of_portion == 2) {
                   
                   $this->ifAlcohol = true;
               }
               $i++; 
        }
        //var_dump($listIdSub);
        //print ($i);
        $this->countProduct = $i;
         $selectIdSub3 = $forwarding_substances
                            ->wherein("id_substances",$listIdSub)
                            //->where
                            ->groupBy("id_products")
                            ->havingRaw("count(id_products) = $i")
                            //->limit($i)
                            ->get();
         $array = array();
         
         $i = 0;
         //var_dump($selectIdSub3);
         foreach ($selectIdSub3 as $selectIdSub4) {
             $array[$i] = $selectIdSub4->id_products;
             $i++;
         }
         //var_dump($array);
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
    public function editRegistration($date,int $idUse,$price) {
        $Use = new usee;
        $Use->where("id",$idUse)->update(["id_products"=>Input::get("nameProduct"),"portion"=>Input::get("portion"),"date"=>$date,"price" => $price]);
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
        $list = $Product->where("id_users",$id_users)
                ->orderBy("name")->get();
        return $list;
        
    }
     
    public function processPrice($listDrugs,$price = "") {
        
        foreach ($listDrugs as $list) {
            if ($price == "") {
              $list->price = $this->calculatePrice($list->price);
            }
            else {
                $list->price = $this->calculatePrice($price);
            }
            
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
        $price = round($price,2);
        if (strstr($price,".")) {
            $div = explode(".",$price);
                if (strlen($div[1]) == 1) {
                    $gr =  $div[1] . "0 Gr";
                }
                else if  (strlen($div[1]) == 2 and $div[1][0] == 0) {
                    $gr =  $div[1][1] . " Gr";
                    
                }
                else if (strlen($div[1]) == 2) {
                    $gr =  $div[1] . " Gr";
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
    public function selectDrugsMonth($year,$month,$id) {
        $calendar = new calendar;
        $howmonth = $calendar->check_month($month,$year);
        for ($i = 0;$i < $howmonth;$i++) {
            $j = $i + 1;
            $this->selectDrugs($id,$year . "-" . $month . "-" . $j);
            $this->dayMonth[$i] = $this->selectColorforday($this->list);
            
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
                    ->where("usees.id",$list->idDrugs)->first();
            
               if (isset($tmp) and $tmp->equivalent != 0 ) {
                   
                $equivalent[$i] = $this->calculateEquivalent($tmp->portion, $tmp->equivalent, 10);
               
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
    public function ifIdIsUsera(string $table,int $id) {
        
        $if = DB::table($table)->where("id",$id)->where("id_users",Auth::User()->id)->first();
        if (empty($if)) {
            return false;
        }
        else {
            return true;
        }
        
    }
    public function selectSubstance(int $id_users) {
        $substance = new Substances;
        $list = $substance->where("id_users",$id_users)
                ->orderBy("name")->get();
        return $list;
    }

    public function selectBenzo($id) {
        $substances = new Substances;
        $list = $substances->where("id_users",$id)
                           ->where("equivalent","!=",0)
                           ->where("equivalent","!=",null)->get();
        return $list;
        
    }
    public function selectBenzoName($id) {
        $substances = new Substances;
        $list = $substances->find($id);
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
        foreach ($drugsList as $list) {
           $i = 0;
           $array = array();
            $idSub = $forwarding_substances->where("id_products",$list->id)->get();
            foreach ($idSub as $idSubstances) {
                $idGru = $forwarding_group->where("id_substances",$idSubstances->id_substances)->get();
                foreach ($idGru as $idgroup) {
                    $color = $group->where("id",$idgroup->id_groups)->get();
                    foreach ($color as $color2) {
                            if ($color2->color == null or $color2->color == 0){
                                continue;
                            }

                                    $array[] += (int) $color2->color;

                            $i++;
                            

                    }
                }
                
            }
            $bool = true;
                $this->colorDrugs[] = $this->colorForDay(array_product(array_unique($array)));
           }
           if (empty($colorarray)) {
               return 0;
           }

    }
     public function selectColorforday($drugsList) {
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
    
    public function selectGroup(int $id_users) {
        $Group = new Group;
        $list = $Group->where("id_users",$id_users)
                ->orderBy("name")->get();
        return $list;
        
    }
    public function selectNameSubstance(int $id) {
        $Substances = new Substances;
        $name = $Substances->where("id_users",Auth::User()->id)->where("id",$id)->first();
        return $name->name;
    }
    public function selectNameProduct(int $id) {
        $Product= new product;
        $name = $Product->where("id_users",Auth::User()->id)->where("id",$id)->first();
        return $name->name;        
    }

}
