<?php 
$app->get('/country/getbyid/:id', function ($id) {
    try 
    {
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM country
            WHERE CountryID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $country = $sth->fetchAll(PDO::FETCH_OBJ);
		if($country) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $country));
        } else {
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "No record found"));
        }
		
    } catch(PDOException $e) {
       $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
 
});

$app->get('/country/get/', function () { 
    try 
    {	$app = \Slim\Slim::getInstance();
		
		$db = getDB();
		$Query="SELECT * FROM country";
        $sth = $db->prepare($Query);
		$sth->execute();
        $country = $sth->fetchAll(PDO::FETCH_OBJ);
        if($country) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=>$country));
        } else {
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "No record found"));
        }
		//echoRespnse($country,$status,$response);
    } catch(PDOException $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/country/add(/)', function() use($app) {
	try 
    {	
		$db = getDB();
		$allPostVars = $app->request->post();
		$CountryName = $allPostVars['CountryName'];
		$Currency = $allPostVars['Currency'];
		
		$qry="SELECT * FROM country WHERE CountryName='".$CountryName."' AND Currency='".$Currency."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $country = $sth->fetchAll(PDO::FETCH_OBJ);
		
		if($country){
		$app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"Country name already exits",$country));
		}else{		
		$sth = $db->prepare("INSERT INTO country (CountryName,Currency) VALUES (:CountryName,:Currency)");
		
		$sth->bindParam(':CountryName', $CountryName);
		$sth->bindParam(':Currency', $Currency);
		$sth->execute();
		$lastInsertedID = $db->lastInsertID();
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","id"=> $lastInsertedID));
		}
    } catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
 
});

$app->post('/country/update/', function() use($app) {
	try 
    {
		$allPostVars = $app->request->post();
		$CountryID = $allPostVars['CountryID'];
		$CountryName = $allPostVars['CountryName'];
		$Currency = $allPostVars['Currency'];
		
        $db = getDB();
        $sth = $db->prepare("UPDATE country SET CountryName=:CountryName, Currency=:Currency WHERE CountryID = :CountryID");
			
		$sth->bindParam(':CountryID', $CountryID);
		$sth->bindParam(':CountryName', $CountryName);
		$sth->bindParam(':Currency', $Currency);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","CountryID"=> $CountryID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});


$app->post('/country/delete/', function() use($app) {
try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$CountryID=$allPostVars['CountryID'];
		
        $db = getDB();
 
        $sth = $db->prepare("Delete From country 
            WHERE CountryID = :CountryID");
 
        $sth->bindParam(':CountryID', $CountryID);
        $sth->execute();
		
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully"));
		
    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		$db = null;
	}
 
});

?>