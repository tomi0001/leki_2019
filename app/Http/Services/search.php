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
    public $arrayFindPro = [0,0];
    public $arrayFindSub = [0,0];
    public $arrayFindGro = [0,0];
    public $type;
    public $string;
    private $sort = "date";
    public $id_product =array();
    public $question;
    public function checkField() {

        
    }
    public function find() {
        $array = array();
        $bool = false;
        if (Input::get("product") != "") {
            $this->arrayFindPro = $this->findString(Input::get("product"),"products");
            $this->type = "products";
            if (count($this->arrayFindPro) != 0) {
                $this->string = $this->arrayFindPro[0][1];
            }
            $this->selectIdProduct();
            $bool = true;
        }
        if (Input::get("substances") != "") {
            $this->arrayFindSub = $this->findString(Input::get("substances"),"substances");
            $this->type = "substances";
            if (count($this->arrayFindSub) != 0) {
                $this->string = $this->arrayFindSub[0][1];
            }
            $this->selectIdSubstances();
            $bool = true;
        }
        if (Input::get("group") != "") {
            $this->arrayFindGro = $this->findString(Input::get("group"),"groups");
            $this->type = "groups";
            if (count($this->arrayFindGro) != 0) {
                $this->string = $this->arrayFindGro[0][1];
            }
            $this->selectIdGroups();
            $bool = true;
        }
        return $bool;
    }
    
    public function checkArrayFind() {
        if ((count($this->arrayFindPro) == 0 or $this->arrayFindPro[0][0] <= 0.5) 
                and (count($this->arrayFindSub) == 0 or $this->arrayFindSub[0][0] <= 0.5) 
                and (count($this->arrayFindGro) == 0 or $this->arrayFindGro[0][0] <= 0.5)) {
            return false;
        }
        return true;
    }
     
     
    public function selectIdProduct() {
        $product = new product;
        $id = $product->where("name",$this->string)->first();
        if (isset($id) ) {
            array_push($this->id_product, $id->id);
        }
        
    }
    public function selectIdSubstances() {
        $substance = new substances;
        $id = $substance->selectRaw("products.id as id")
                ->join("forwarding_substances","forwarding_substances.id_substances","substances.id")
                ->join("products","products.id","forwarding_substances.id_products")
                ->where("substances.name",$this->string)->get();
        $i = 0;
        foreach ($id as $id_product) {
            array_push($this->id_product,$id_product->id);
            $i++;
        }
        
    }
    public function selectIdGroups() {
        $group = new group;
        $id = $group->selectRaw("products.id as id")
                ->selectRaw("products.name as name")
                ->join("forwarding_groups","groups.id","forwarding_groups.id_groups")
                ->join("substances","substances.id","forwarding_groups.id_substances")
                ->join("forwarding_substances","substances.id","forwarding_substances.id_substances")
                ->join("products","products.id","forwarding_substances.id_products")
                ->where("groups.name",$this->string)->get();
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
    
    public function selectDrugs($dateStart,$dateEnd) {
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
                        ->where("usees.id_users",Auth::User()->id)
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
    
    public function createQuestions($bool) {
        $drugs = new drugs;
        $this->question =  usee::query();
        $hour = $this->selectHourStart(Auth::User()->id);
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
                ->where("usees.id_users",Auth::User()->id);

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
            print $list2->dat;
            //if ($list2->dat == "") {
              //  print "dd";
                //$day[$i][1] = 0;
                //$day[$i][2] = 0;
                //$day[$i][3] = 0;
                
            //}
            //else {
                $tmp = explode("-",$list2->dat);
                $day[$i][1] = $tmp[0];
                $day[$i][2] = $tmp[1];
                $day[$i][3] = $tmp[2];
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
    
    private function findString($search,$table) {
        $array = array();
        $find = DB::table($table)->where("id_users",Auth::User()->id)->get();
        $i = 0;
        foreach ($find as $find2) {
             $find3 = DB::table($table)->get();

                $result = $this->findSuchString($search,$find2->name);
                if ($result >= 0) {
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
        return $correct / $result;
      }
    
    
}