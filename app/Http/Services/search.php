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

class search
{   
    public $arrayFindPro = [array(),0,0];
    public $arrayFindSub = [array(),0,0];
    public $arrayFindGro = [array(),0,0];
    public $type;
    public $stringPro = [];
    public $stringSub = [];
    public $stringGro = [];
    private $sort = "date";
    private $string2 = [];
    private $string3 = [];
    public $id_product =array();
    public $question;
    public $bool = false;
    public function checkField() {

        
    }
    
    private function divSearchString($string,$type) {
        $array = [];
        if (strstr($string,",")) {
            $array = explode(",",$string);
            for ($i=0;$i < count($array);$i++) {
                switch ($type) {
                    case "products":
                        array_push($this->stringPro, $array[$i]);
                        break;
                    case "substances":
                        array_push($this->stringSub, $array[$i]);
                        break;
                    case "group":
                        array_push($this->stringGro, $array[$i]);
                        break;
                }
            }
        }
        else {
            switch ($type) {
                case "products":
                    array_push($this->stringPro, $string);
                    break;
                case "substances":
                     array_push($this->stringSub, $string);
                    break;
                case "group": 
                    array_push($this->stringGro, $string);
                    break;
            }
        }
    }
    /*
    public function findNot() {
        $array = array();
        //$bool = false;
        
        if (Input::get("productNot") != "") {
            $this->divSearchString(Input::get("productNot"),"products");
            for ($i=0;$i < count($this->stringPro);$i++) {
                $this->arrayFindPro[$i] = $this->findString($this->stringPro[$i],"products");
            }
            $this->type = "products";
            if (count($this->arrayFindPro) != 0) {
                for ($i=0;$i < count($this->stringPro);$i++) {
                    $this->string2[$i] = $this->arrayFindPro[$i][0][1];
                }
            }
            $this->selectIdProduct();
            $this->bool = true;
        }
        
        if (Input::get("substancesNot") != "") {
            //print "dos";
            $this->divSearchString(Input::get("substancesNot"),"substances");
                        for ($i=0;$i < count($this->stringSub);$i++) {

                                $this->arrayFindSub[$i] = $this->findString($this->stringSub[$i],"substances");
                        }
            $this->type = "substances";
            if (count($this->arrayFindSub) != 0) {
                for ($i=0;$i < count($this->stringSub);$i++) {
                    $this->string2[$i] = $this->arrayFindSub[$i][0][1];
                }
            }
            $this->selectIdSubstances(true);
            $this->bool = true;
        }
        if (Input::get("groupNot") != "") {
            //print "dd";
            $this->divSearchString(Input::get("groupNot"),"group");
            for ($i=0;$i < count($this->stringGro);$i++) {
                $this->arrayFindGro[$i] = $this->findString($this->stringGro[$i],"groups");
            }
            $this->type = "groups";
            if (count($this->arrayFindGro) != 0) {
                for ($i=0;$i < count($this->stringGro);$i++) {
                    $this->string3[$i] = $this->arrayFindGro[$i][0][1];
                }
            }
            $this->selectIdGroups(true);
            $this->bool = true;
        }
        //return $bool;
    }
     * 
     */
    public function find($id) {
        $array = array();
        //$bool = false;
        //print "s";
        if (Input::get("product") != "") {
            $this->divSearchString(Input::get("product"),"products");
            for ($i=0;$i < count($this->stringPro);$i++) {
                $this->arrayFindPro[$i] = $this->findString($this->stringPro[$i],"products",$id);
                //print "dd";
            }
            $this->type = "products";
            //print ("<pre>");
            //print_r($this->arrayFindPro);
            //print ("</pre>");
            //print $this->arrayFindPro[1][0][1];
            $z = 0;
            for ($j=0;$j < count($this->stringPro);$j++) {
                if (count($this->arrayFindPro[$j]) ) {
                    for ($i=0;$i < count($this->stringPro);$i++) {
                        if (isset($this->arrayFindPro[$i][0][1]) ) {
                            $this->string2[$z] = $this->arrayFindPro[$i][0][1];
                        }
                        $z++;
                    }
                }
            }
            $this->selectIdProduct();
            $this->bool = true;
        }
        
        if (Input::get("substances") != "") {
            $this->divSearchString(Input::get("substances"),"substances");
                        for ($i=0;$i < count($this->stringSub);$i++) {

                                $this->arrayFindSub[$i] = $this->findString($this->stringSub[$i],"substances",$id);
                        }
            $this->type = "substances";
            $z = 0;
            for ($j=0;$j < count($this->stringSub);$j++) {
                if (count($this->arrayFindSub[$j]) != 0) {
                    for ($i=0;$i < count($this->stringSub);$i++) {
                        if (isset($this->arrayFindSub[$i][0][1]) ) {
                            $this->string2[$z] = $this->arrayFindSub[$i][0][1];
                        }
                        
                        $z++;
                    }
                }
            }
            $this->selectIdSubstances();
            $this->bool = true;
        }
        if (Input::get("group") != "") {
            $this->divSearchString(Input::get("group"),"group");
            for ($i=0;$i < count($this->stringGro);$i++) {
                $this->arrayFindGro[$i] = $this->findString($this->stringGro[$i],"groups",$id);
            }
            $this->type = "groups";
            $z = 0;
            for ($j=0;$j < count($this->stringGro);$j++) {
                if (count($this->arrayFindGro[$j]) != 0) {
                    for ($i=0;$i < count($this->stringGro);$i++) {
                         if (isset($this->arrayFindGro[$i][0][1]) ) {
                            $this->string2[$z] = $this->arrayFindGro[$i][0][1];
                         }
                         $z++;
                    }
                }
            }
            $this->selectIdGroups();
            $this->bool = true;
        }
        //$this-findNot();
        //return $bool;
    }
    
    public function checkArrayFindPro($count) {
        $z = 0;
        for ($i=0;$i < $count;$i++) {
            if (!isset($this->arrayFindPro[$i]) and (count($this->arrayFindPro[$i]) == 0) or 
                    (!isset($this->arrayFindPro[$i][0][0]) or $this->arrayFindPro[$i][0][0] <= 0.5) 
                    ) {
                //return false;
                $z++;
            }
        
            //return true;
        }
        if ($z == $i) {
            return false;
        }
        else {
            return true;
        }
    }
    public function checkArrayFindSub($count) {
        $z = 0;
        for ($i=0;$i < $count;$i++) {
            if (!isset($this->arrayFindSub[$i]) and (count($this->arrayFindSub[$i]) == 0) or 
                    (!isset($this->arrayFindSub[$i][0][0]) or $this->arrayFindSub[$i][0][0] <= 0.5) 
                    ) {
                $z++;
            }
        
            //return true;
        }
        if ($z == $i) {
            return false;
        }
        else {
            return true;
        }
    }
    public function checkArrayFindGro($count) {
        $z = 0;
        for ($i=0;$i < $count;$i++) {
            if (!isset($this->arrayFindGro[$i]) and (count($this->arrayFindGro[$i]) == 0) or 
                    (!isset($this->arrayFindGro[$i][0][0]) or $this->arrayFindGro[$i][0][0] <= 0.5) 
                    ) {
                $z++;
            }
        
            //return true;
        }
        if ($z == $i) {
            return false;
        }
        else {
            return true;
        }
    }
     
    public function selectIdProduct() {
        $product = new product;
        $id = $product->whereIn("name",$this->string2)->get();
        if (isset($id) ) {
            foreach ($id as $id2) {
                array_push($this->id_product, $id2->id);
            }
        }
        
    }
    public function selectIdSubstances($not = false) {
        $substance = substances::query();
       $substance->selectRaw("products.id as id")
                ->join("forwarding_substances","forwarding_substances.id_substances","substances.id")
                ->join("products","products.id","forwarding_substances.id_products");
                if ($not == false) {
                    $substance->whereIn("substances.name",$this->string2);
                }
                else {
                    $substance->whereNotIn("substances.name",$this->string3);
                }
                //->whereIn("substances.name",$this->string2)->get();
       
        $id = $substance->get();
        $i = 0;
        foreach ($id as $id_product) {
            array_push($this->id_product,$id_product->id);
            $i++;
        }
        
    }
    public function selectIdGroups($not = false) {
        //$group = new group;
        $group = group::query();
         $group->selectRaw("products.id as id")
                ->selectRaw("products.name as name")
                ->join("forwarding_groups","groups.id","forwarding_groups.id_groups")
                ->join("substances","substances.id","forwarding_groups.id_substances")
                ->join("forwarding_substances","substances.id","forwarding_substances.id_substances")
                ->join("products","products.id","forwarding_substances.id_products");
                if ($not == false) {
                    $group->whereIn("groups.name",$this->string2);
                }
                else {
                    $group->whereNotIn("groups.name",$this->string3);
                }
               $id =  $group->get();
        $i = 0;
        foreach ($id as $id_product) {
            array_push($this->id_product,$id_product->id);
            $i++;
        }
        
    }
    private function selectHourStart(int $id_users) {
        $user = new User;       
        $hour = $user->where("id",$id_users)->first();
        return $hour->start_day;
        
    }
    private function setSort() {
        if (Input::get("sort") == "portion" and Input::get("day") != "") {
            $this->sort = "por";
        }
        else if (Input::get("sort") == "portion") {
            $this->sort = "portion";
        }
        else if (Input::get("sort") == "product") {
            $this->sort = "product";
        }
        else if (Input::get("sort") == "hour") {
            $this->sort = "hour";
        }
    }
    private function setWhere($bool,$search) {
        if (count($this->id_product) == 0 and (Input::get("data1") == "" or Input::get("data2") == "")) {
            
            $data2 = date("Y-m-d");
            $data1 = date("Y-m-d", time() - 2592000);
        }
        else {
            $data1 = Input::get("data1");
            $data2 = Input::get("data2");
        }
        if ($data1 != "") {
            $this->question->where("usees.date",">=",$data1);
        }
        if ($data2 != "") {
            $this->question->where("usees.date","<=",$data2);
        }
        if (Input::get("dose1") != "" and Input::get("day") == "") {
            $this->question->where("usees.portion",">=",Input::get("dose1"));
        }
        if (Input::get("dose2") != "" and Input::get("day") == "") {
            $this->question->where("usees.portion","<=",Input::get("dose2"));
        }
        if (Input::get("hour1") != "") {
            $this->question->whereRaw("hour(usees.date) >=  " . Input::get("hour1"));
        }
        if (Input::get("hour2") != "")  {
            $this->question->whereRaw("hour(usees.date)<=" . Input::get("hour2"));
        }
        if (Input::get("search") != "") {
            $this->question->where("descriptions.description","like","%" . $search . "%");
        }
        if (Input::get("inDay") != "") {
            $this->question->where("descriptions.description","!=", "");
        }
        
        if ($bool == true) {
                $this->question->whereIn("products.id",$this->id_product);
        }
        
        
    }
    private function setGroup($hour) {
               if (Input::get("day") != "") {
                    $this->question->groupBy(DB::Raw("(DATE(IF(HOUR(usees.date) >= '$hour', usees.date,Date_add(usees.date, INTERVAL - 1 DAY) )) )"));
                    if (Input::get("dose1") != "" ) {
                      $this->question->havingRaw("sum(usees.portion) >= " . Input::get("dose1"));
                    }
                    if (Input::get("dose2") != "" ) {
                      $this->question->havingRaw("sum(usees.portion) <= " . Input::get("dose2"));
                    }
                }
                else {
                    $this->question->groupBy("usees.id");
                }
        
    }
    
    public function selectDrugs($dateStart,$dateEnd,$id) {
        $drugs = new drugs;
        //$drugs
        $this->question =  usee::query();
        $product = $this->question
                        ->selectRaw("products.name as products")
                        ->selectRaw("usees.id as id2")
                        ->selectRaw("usees.id_products as id")
                        ->join("products","usees.id_products","products.id")
                        ->where("date",">=",$dateStart)
                        ->where("date","<=",$dateEnd)
                        ->where("usees.id_users",$id)
                        ->groupBy("usees.id_products")
                ->paginate(10);
        
        return $product;
        //return $product;
        /*
        
        foreach ($product as $list) {
            //print ("<pre>");
            $array = $drugs->returnIdProduct($list->id);
            $hourDrugs = $drugs->sumAverage($list,$dateStart,$dateEnd);
               $array = array();
             for ($i=0;$i < count($hourDrugs);$i++) {
                $array[$i] = $drugs->sumDifferentDay($hourDrugs[$i][1],$hourDrugs[$i][2]);
                print $array[$i];
                 
             }
            //print($a->products) . "<br>";
        }
         
         
         * 
         */
    }
    
    
    
    public function sumAverage() {
         $Drugs = new Drugs;
         $Hash = new Hashs();
         $user = new user();
         if ( (Auth::check()) ) {
             $id = Auth::User()->id;
             $startDay = Auth::User()->start_day;
         }
         else if ($Hash->checkHashLogin() == true) {
             $user->updateHash();
             $id = $Hash->id;
             $startDay = $Hash->start;
         }
         else {
             return;
         }
            $bool = $Drugs->checkDrugs($id,Input::get("id"));
            if ($bool == true) {
                $list = $Drugs->returnIdProduct(Input::get("id"));
                $date = $Drugs->returnDateDrugs(Input::get("id"));
                $hourDrugs = $Drugs->sumAverageCount($list,$date,$Drugs->ifAlcohol,$id,$startDay);
                $array = array();
                for ($i=0;$i < count($hourDrugs);$i++) {
                   $array[$i] = $Drugs->sumDifferentDay($hourDrugs[$i][1],$hourDrugs[$i][2]);

                }
                return View("ajax.sum_average")->with("arrayDay",$array)->with("hourDrugs",$hourDrugs);
            }
         
     }
    public function createQuestions($bool,$id) {
        $drugs = new drugs;
        $this->question =  usee::query();
        $hour = $this->selectHourStart($id);
        $search = $drugs->charset_utf_fix2(Input::get("search"));
        $this->question
                ->select( DB::Raw("(DATE(IF(HOUR(usees.date) >= '$hour', usees.date,Date_add(usees.date, INTERVAL - 1 DAY) )) ) as dat  "))   
                ->selectRaw("hour(usees.date) as hour")
                ->selectRaw("round(sum(usees.portion),2) as por")
                ->selectRaw("day(usees.date) as day")
                ->selectRaw("month(usees.date) as month")
                ->selectRaw("year(usees.date) as year")                
                ->selectRaw("usees.portion as portion")
                ->selectRaw("usees.date as date")
                ->selectRaw("usees.id_products as id")
                ->selectRaw("usees.id as id_usees")
                ->selectRaw("descriptions.description as description")
                ->selectRaw("descriptions.date as date_description")
                ->selectRaw("usees.id_products as product")
                ->selectRaw("products.name as name")
                ->selectRaw("products.type_of_portion as type")
                ->leftjoin("forwarding_descriptions","usees.id","forwarding_descriptions.id_usees")
                ->leftjoin("descriptions","descriptions.id","forwarding_descriptions.id_descriptions")
                ->leftjoin("products","products.id","usees.id_products")
                ->where("usees.id_users",$id);

        $this->setWhere($bool,$search);
        $this->setGroup($hour);
         
             $this->setSort();
            $this->question->orderBy($this->sort,"DESC");
            $list = $this->question->paginate(10);

        return $list;
        
    }

    
    public function changeArray($list) {
        $day = array();
        $i = 0;
        $tmp = array();
        foreach ($list as $list2) {
            
            $day[$i][0] = $list2->dat;
            //print $list2->dat;
            //if ($list2->dat == "") {
              //  print "dd";
                //$day[$i][1] = 0;
                //$day[$i][2] = 0;
                //$day[$i][3] = 0;
                
            //}
            //else {
            if (strstr($list2->dat,"-") ) {
                $tmp = explode("-",$list2->dat);
                $day[$i][1] = $tmp[0];
                $day[$i][2] = $tmp[1];
                $day[$i][3] = $tmp[2];
            }
            else {
                $day[$i][1] = "";
                $day[$i][2] = "";
                $day[$i][3] = "";
            }
            //}
            switch ($list2->type) {
                case 1: 
                    $day[$i][4] = "Mg";
                break;
                case 2: 
                    $day[$i][4] = "militry";
                break;
                default:
                    $day[$i][4] = "ilości";
                
            }
            $i++;
        }
        return $day;
    }
    
    private function findString($search,$table,$id) {
        $array = array();
        $find = DB::table($table)->where("id_users",$id)->get();
        $i = 0;
        foreach ($find as $find2) {
             $find3 = DB::table($table)->get();

                $result = $this->findSuchString($search,$find2->name);
                if ($result >= 0.5) {
                   $array[$i][0] =  $result;
                   $array[$i][1] =  $find2->name;
                 
                   $i++;
                }

        }

        rsort($array);
        return $array;
        
    }
    private function findSuchString($text1,$text2) {
  
        $how1 = strlen($text1);
        $how2 = strlen($text2);

        if ($how1 > $how2) $how = $how1;
        else $how = $how2;
        $correct = 0;
        for ($i=0;$i< $how;$i++) {

            if (isset($text1[$i]) and isset($text2[$i]) and $text1[$i] != $text2[$i] ) $correct--;
            else if (isset($text1[$i]) and isset($text2[$i]) and  $text1[$i] == $text2[$i]) $correct++;
        }
        $result = ($how1 + $how2) / 2;
        if ($result == 0) {
            $result = 1;
        }
        return $correct / $result;
      }
    
    
}