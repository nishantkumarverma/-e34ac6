<?php 
$app->get('/city/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM city
            WHERE CityID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $city = $sth->fetchAll(PDO::FETCH_OBJ);
		if($city) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $city));
   
        } else {
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "No record found"));
        }

    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
 
});


$app->get('/city/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    try 
    {
		$app = \Slim\Slim::getInstance();
		
		$Query="SELECT s.*,c.StateName FROM city s, states c where c.StateID=s.StateID order by CityName DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
		$db = getDB();
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $city = $sth->fetchAll(PDO::FETCH_OBJ);

        if($city) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $city));
        } else {
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "No record found"));
        }
    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/city/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		$StateID = $allPostVars['StateID'];
		$CityName = $allPostVars['CityName'];
		
		$qry="SELECT * FROM city WHERE StateID='".$StateID."' AND CityName='".$CityName."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $city = $sth->fetchAll(PDO::FETCH_OBJ);

		if($city){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"city name already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO city (StateID,CityName) VALUES (:StateID,:CityName)");
		$sth->bindParam(':StateID', $StateID);
		$sth->bindParam(':CityName', $CityName);
		$sth->execute();
		$lastInsertedID = $db->lastInsertID();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","id"=> $lastInsertedID));
		}
    } catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
 
});

$app->post('/city/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$CityID = $allPostVars['CityID'];
	    $StateID = $allPostVars['StateID'];
		$CityName = $allPostVars['CityName'];
			
        $db = getDB();
        $sth = $db->prepare("UPDATE city SET StateID=:StateID, CityName=:CityName WHERE CityID = :CityID");
			
		$sth->bindParam(':StateID', $StateID);
		$sth->bindParam(':CityName', $CityName);
		$sth->bindParam(':CityID', $CityID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","CityID"=> $CityID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/city/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$CityID=$allPostVars['CityID'];
		$db = getDB();
        $sth = $db->prepare("Delete From city 
            WHERE CityID = :CityID");
 
        $sth->bindParam(':CityID', $CityID);
        $sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully"));
		
    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		$db = null;
	}
 
});

?>