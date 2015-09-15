<?php 
$app->get('/states/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM states
            WHERE StateID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $states = $sth->fetchAll(PDO::FETCH_OBJ);
		if($states) { 
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $states));
   
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


$app->get('/states/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    try 
    {
		$app = \Slim\Slim::getInstance();
		
		$Query="SELECT s.*,c.CountryName,c.Currency FROM states s, country c where c.CountryID=s.CountryID order by StateName DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
		$db = getDB();
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $states = $sth->fetchAll(PDO::FETCH_OBJ);

        if($states) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $states));
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

$app->post('/states/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
		$CountryID = $allPostVars['CountryID'];
		$StateName = $allPostVars['StateName'];
		
		$qry="SELECT * FROM states WHERE CountryID='".$CountryID."' AND StateName='".$StateName."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $states = $sth->fetchAll(PDO::FETCH_OBJ);

		if($states){
		$app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"states name already exits"));
		}else{		
		$sth = $db->prepare("INSERT INTO states (CountryID,StateName) VALUES (:CountryID,:StateName)");
		$sth->bindParam(':CountryID', $CountryID);
		$sth->bindParam(':StateName', $StateName);
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

$app->post('/states/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$StateID = $allPostVars['StateID'];
	    $CountryID = $allPostVars['CountryID'];
		$StateName = $allPostVars['StateName'];
			
        $db = getDB();
        $sth = $db->prepare("UPDATE states SET CountryID=:CountryID, StateName=:StateName WHERE StateID = :StateID");
			
		$sth->bindParam(':CountryID', $CountryID);
		$sth->bindParam(':StateName', $StateName);
		$sth->bindParam(':StateID', $StateID);
		$sth->execute();
		
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","StateID"=> $StateID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/states/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$StateID=$allPostVars['StateID'];
		$db = getDB();
        $sth = $db->prepare("Delete From states 
            WHERE StateID = :StateID");
 
        $sth->bindParam(':StateID', $StateID);
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