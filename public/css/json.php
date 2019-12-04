<?php

use rare\model\Property;

require __DIR__."/../vendor/autoload.php";
header("Content-Type: application/json");
session_start();

if (!isset($_SESSION["auth_user"])) print(json_encode([]));
else if (isset($_GET["action"])) {

    switch ($_GET["action"]) {
        case "save_address":

            $saved = new \stdClass();
            $property = json_decode(file_get_contents("php://input"));
            Property::update(
                $_GET["id"],
                [
                    "zipcode" => $property->zipcode,
                    "city" => $property->city,
                    "street" => $property->street,
                    "houseNumber" => $property->houseNumber.$property->houseNumberExtension,
                    "district" => $property->district,
                    "region" => $property->region,
                    "location" => $property->location,
                    "atlas_id" => $property->_id
                ],
                $_GET["source"]
            );
            $saved->success = true;
            print(json_encode($saved));
            break;
        case "licytacje":
            
            $property = json_decode(file_get_contents("php://input"));
            
            print json_encode($property->city);
            
            break;
    }

} else {

    
    
    
    $http = curl_init();
    curl_setopt_array($http,[
        CURLOPT_URL => "https://system.rausch.haus".$_SERVER["REQUEST_URI"].
            (strpos($_SERVER["REQUEST_URI"], "&id") === false ? "&id=none" : ""),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [ "api_user" => "system", "api_password" => "dOH8eZFTv3mRSOcM" ]
    ]);

    print(curl_exec($http));
    curl_close($http);
    
    //$myJSON = json_encode([$_GET["term"]]);
     //print $myJSON;
     
    
    //if ($_GET["search"] == "city") {
        //$array = \rare\ctrl\ObjekteControllerAufgabenSearch::divisionstring($_GET["term"]);
        /*
        //$select = \rare\ctrl\model\stringsearch::search($array);
        //if ($_GET["search"] == "city") {
        $select = \rare\model\stringsearch::search($_GET["term"]);
        $string = \rare\ctrl\ObjekteControllerAufgabenSearch::convertToText($select);

        $myJSON = json_encode([$_GET["search"]]);
        print $myJSON;
        //}
        if ($_GET["search"] == "street") {
        $select = \rare\model\stringsearch::search($_GET["term"]);
        $string = \rare\ctrl\ObjekteControllerAufgabenSearch::convertToText($select);

        $myJSON = json_encode(["ss"]);
        print $myJSON;
        }
    //}
   
    //$myObj[0] = "John";
//$myObj[0] = "ss";
//$myObj[2] = "New York";
//$property = (file_get_contents("php://input"));
//$array[0] = $_POST["city"];
//$array[0] = "dos";
//$array = \rare\ctrl\ObjekteControllerAufgabenSearch::selectString($_GET["term"]);
//$myJSON = json_encode($property);
//print "sss";
//foreach ($property as $s) {
  //  print( "ss");
//}
   
         * 
         */
}

