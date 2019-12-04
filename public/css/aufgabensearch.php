<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace rare\ctrl;

/**
 * Description of ObjekteControllerAufgabenSearch
 *
 * @author tomi5
 */
class ObjekteControllerAufgabenSearch {
    //public $arrayString = [];
    //public $array = [];
    public function divisionstring($city) {
        
            $arrayString = array();
            $array = array();
            array_push($arraystring, $city);
            array_push($arrayString, $city);
            $text = str_replace("ss", "ß", $city);
            array_push($arrayString, $text);
            $text = str_replace(" ", "", $city);
            array_push($arrayString, $text);

            $text = str_replace(" ", "", $city);
            array_push($arrayString, $text);
            
                for ($i=0;$i < count($arrayString);$i++) {
                    $result = strlen($arrayString[$i]);
                    if (strlen($city) > 4) {
                        if (($result % 2) != 0) {
                            $leftresult = ((int) $result / 2) + 1;
                            $rightresult = (int) $result / 2;
                        }
                        else {
                            $leftresult = ((int) $result / 2);
                            $rightresult = (int) $result / 2;
                        }
                    }
                    else {
                        $leftresult = strlen($city);
                        $rightresult = 0;
                    }
                   $text =    substr( $arrayString[$i],0, $leftresult);
                array_push($array, $text);
                $text = substr( $arrayString[$i], $rightresult,strlen($city));
                array_push($array, $text);
                    //$substr_replace($text, $replacement, 0,$leftresult);
                
            }
          
            return $array;
        //\rare\model\stringsearch::selectAufgabetodo();
        
    }
    
    public function convertToText($array) {
        $array2 = [];
        $i = 0;
        foreach ($array as $select2) {
            $array2[$i] = $select2["zvgportal_lage"];
            $i++;
        }
        return $array2;
    }
}
