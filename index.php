<?php

require_once('lib/flight/Flight.php');
require_once('lib/medoo.php');

$base_href = 'https://googlemapspanel.herokuapp.com/';
$db = new medoo(array(
	'database_type' => 'mysql',	
    'database_name' => 'db98354f782b7743daab35a67b00b63513',
    'server' => '98354f78-2b77-43da-ab35-a67b00b63513.mysql.sequelizer.com',
    'username' => 'qoeamfkogizukyqs',
    'password' => 'yHpT5ZGTv8g34aUqrjksDTz8Tx7xan7xg6fNf3fUayNDeENpHNFoUumMhp8E2jTh',
	'charset' => 'utf8'
));

/*
$base_href = 'http://localhost/GoogleMapsPanel/';
$db = new medoo(array(
	'database_type' => 'mysql',	
    'database_name' => 'maps',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '74123698',
	'charset' => 'utf8'
));
*/

Flight::map('authorization', function($request){	
	$headers = parseRequestHeaders($request->data);	
	if(!array_key_exists("Auth", $headers)){
		Flight::halt(401, "Unauthorized");
	}else if ($headers["Auth"] != 'ZGF0YXBhcms6b3BlbmNhcnQ='){
		Flight::halt(401, "Unauthorized");
	}
});

Flight::route('/', function(){	
	Flight::redirect('map');
});

Flight::route('/map', function(){	
	Flight::render('map.php');
});

Flight::route('/admin', function(){		
	//Flight::authorization( Flight::request() );
	global $db;
	
	$lokasyon = $db->select("lokasyon", 
		array("[><]sehir s" => array("sehir" => "il_id"), "[><]ilce i" => array("ilce" => "ilce_id")), 
		array("lokasyon.*", "sehir.il_adi", "ilce.ilce_adi")
	);

	Flight::render('admin.php', array("lokasyonListesi" => $lokasyon));
});

Flight::route('POST /admin/lokasyon/kaydet', function(){	
	//Flight::authorization( Flight::request() );
	global $db;
	
	$request = Flight::request();
	
	$required_fields = array('baslik',
							'adres',
							'ilce',
							'sehir',
							'telefon',
							'web',
							'lat',
							'lng',
							'ikon');
	$request_keys = reset($request->data);
	foreach($required_fields as $required_field){
		if(!array_key_exists($required_field, $request_keys)){
			Flight::halt(400, json_encode( array("Gerekli bilgiler: " => implode(',', $required_fields)) ));
		}
	}
	
	if( $db->has( "lokasyon", array("id" => $request->data["id"])) ){
		$lokasyon_id = $db->update("lokasyon", array(
			"baslik" => $request->data["baslik"],
			"adres" => $request->data["adres"],
			"ilce" => intval($request->data["ilce"]),
			"sehir" => intval($request->data["sehir"]),
			"telefon" => $request->data["telefon"],
			"web" => $request->data["web"],
			"lat" => floatval($request->data["lat"]),
			"lng" => floatval($request->data["lng"]),
			"ikon" => $request->data["ikon"]				
		), array("id" => $request->data["id"]));
	}else{
		$lokasyon_id = $db->insert("lokasyon", array(
			"baslik" => $request->data["baslik"],
			"adres" => $request->data["adres"],
			"ilce" => intval($request->data["ilce"]),
			"sehir" => intval($request->data["sehir"]),
			"telefon" => $request->data["telefon"],
			"web" => $request->data["web"],
			"lat" => floatval($request->data["lat"]),
			"lng" => floatval($request->data["lng"]),
			"ikon" => $request->data["ikon"]				
		));
	}
	
	$lokasyon = $db->select("lokasyon", 
		array("[><]sehir s" => array("sehir" => "il_id"), "[><]ilce i" => array("ilce" => "ilce_id")), 
		array("lokasyon.*", "sehir.il_adi", "ilce.ilce_adi"),
		array("lokasyon.id" => $lokasyon_id)
	);
	
	Flight::json( array('ResultValue' => true, 'ResultText' => 'Lokasyon kayıt edildi.', 'Data' => $lokasyon) );
	//Flight::render('admin.php', array('ResultValue' => true, 'ResultText' => 'Lokasyon kayıt edildi.', 'Data' => $lokasyon));
});

Flight::route('POST /admin/lokasyon/sil/@id:[0-9]+', function($id){	
	//Flight::authorization( Flight::request() );
	global $db;
	
	$request = Flight::request();
		
	$lokasyon = $db->delete("lokasyon", array("id" => $id));	
	
	Flight::json( array('ResultValue' => true, 'ResultText' => 'Lokasyon silindi.', 'Data' => $lokasyon) );	
});

Flight::route('/admin/lokasyon/liste(/@ilce_id:[0-9]+)', function($ilce_id){	
	//Flight::authorization( Flight::request() );
	global $db;
	
	if($ilce_id > 0){
		/*
		$lokasyon = $db->select("lokasyon", 
			array("[><]sehir s" => array("sehir" => "il_id"), "[><]ilce i" => array("ilce" => "ilce_id")), 
			array("lokasyon.*", "sehir.il_adi", "ilce.ilce_adi"),
			array("lokasyon.ilce"=> $ilce_id)
		);			
		*/
		$lokasyon = $db->query("SELECT lokasyon.*,sehir.il_adi,ilce.ilce_adi 
								FROM lokasyon 
								INNER JOIN sehir ON lokasyon.sehir = sehir.il_id 
								INNER JOIN ilce ON lokasyon.ilce = ilce.ilce_id
								WHERE lokasyon.ilce = " . intval($ilce_id))->fetchAll(PDO::FETCH_ASSOC);		
	}else{
		$lokasyon = $db->query("SELECT lokasyon.*,sehir.il_adi,ilce.ilce_adi 
						FROM lokasyon 
						INNER JOIN sehir ON lokasyon.sehir = sehir.il_id 
						INNER JOIN ilce ON lokasyon.ilce = ilce.ilce_id")->fetchAll(PDO::FETCH_ASSOC);	
	}	
	
	Flight::json( $lokasyon );
});

Flight::start();