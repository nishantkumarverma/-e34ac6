<?php 
$app->get('/manufacturer/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM productmanufacturer
            WHERE ManufacturerID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Manufacturer = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Manufacturer) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Manufacturer));
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

$app->get('/manufacturer/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM productmanufacturer order by ManufacturerName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
 
        $sth->execute();
 
        $Manufacturer = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Manufacturer) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Manufacturer));
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
$app->post('/manufacturer/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
	
		$ManufacturerName=$allPostVars['ManufacturerName'];
		$ManufacturerDescription=$allPostVars['ManufacturerDescription'];
		$ManufacturerLogo=$allPostVars['ManufacturerLogo'];
		$ManufacturerIsActive=$allPostVars['ManufacturerIsActive'];
		$ManufacturerEmail=$allPostVars['ManufacturerEmail'];
		$ManufacturerPassword=$allPostVars['ManufacturerPassword'];
		$ManufacturerMobile=$allPostVars['ManufacturerMobile'];
		$ManufacturerCompanyID=$allPostVars['ManufacturerCompanyID'];
		
		$sth = $db->prepare("SELECT * 
            FROM productmanufacturer
            WHERE ManufacturerName = :ManufacturerName And ManufacturerCompanyID=:ManufacturerCompanyID");
 
        $sth->bindParam(':ManufacturerName', $ManufacturerName);
		$sth->bindParam(':ManufacturerCompanyID', $ManufacturerCompanyID);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Manufacturer name already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO productmanufacturer(ManufacturerName ,ManufacturerDescription ,ManufacturerLogo ,ManufacturerIsActive ,ManufacturerEmail ,ManufacturerPassword ,ManufacturerMobile ,ManufacturerCompanyID )            VALUES(:ManufacturerName,:ManufacturerDescription,:ManufacturerLogo,:ManufacturerIsActive,:ManufacturerEmail,:ManufacturerPassword,:ManufacturerMobile,:ManufacturerCompanyID)");
			

        $sth->bindParam(':ManufacturerName', $ManufacturerName);
		$sth->bindParam(':ManufacturerCompanyID', $ManufacturerCompanyID);
		$sth->bindParam(':ManufacturerDescription', $ManufacturerDescription);
		$sth->bindParam(':ManufacturerLogo', $ManufacturerLogo);
		$sth->bindParam(':ManufacturerIsActive', $ManufacturerIsActive);
		$sth->bindParam(':ManufacturerEmail', $ManufacturerEmail);
		$sth->bindParam(':ManufacturerPassword', $ManufacturerPassword);
		$sth->bindParam(':ManufacturerMobile', $ManufacturerMobile);
        $sth->execute();
		$lastInsertId = $db->lastInsertId();
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted Successfully!","id"=> $lastInsertId));
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
$app->post('/manufacturer/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
    $ManufacturerID=$allPostVars['ManufacturerID'];
	$ManufacturerName=$allPostVars['ManufacturerName'];
		$ManufacturerDescription=$allPostVars['ManufacturerDescription'];
		$ManufacturerLogo=$allPostVars['ManufacturerLogo'];
		$ManufacturerIsActive=$allPostVars['ManufacturerIsActive'];
		$ManufacturerEmail=$allPostVars['ManufacturerEmail'];
		$ManufacturerPassword=$allPostVars['ManufacturerPassword'];
		$ManufacturerMobile=$allPostVars['ManufacturerMobile'];
		$ManufacturerCompanyID=$allPostVars['ManufacturerCompanyID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE productmanufacturer 
            SET ManufacturerName = :ManufacturerName, ManufacturerCompanyID=:ManufacturerCompanyID,ManufacturerDescription = :ManufacturerDescription, ManufacturerLogo=:ManufacturerLogo,ManufacturerIsActive = :ManufacturerIsActive, ManufacturerEmail=:ManufacturerEmail,ManufacturerPassword = :ManufacturerPassword, ManufacturerMobile=:ManufacturerMobile
            WHERE ManufacturerID = :ManufacturerID");
 
        $sth->bindParam(':ManufacturerName', $ManufacturerName);
		$sth->bindParam(':ManufacturerCompanyID', $ManufacturerCompanyID);
		$sth->bindParam(':ManufacturerDescription', $ManufacturerDescription);
		$sth->bindParam(':ManufacturerLogo', $ManufacturerLogo);
		$sth->bindParam(':ManufacturerIsActive', $ManufacturerIsActive);
		$sth->bindParam(':ManufacturerEmail', $ManufacturerEmail);
		$sth->bindParam(':ManufacturerPassword', $ManufacturerPassword);
		$sth->bindParam(':ManufacturerMobile', $ManufacturerMobile);
        $sth->bindParam(':ManufacturerID', $ManufacturerID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $ManufacturerID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/manufacturer/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$ManufacturerID=$allPostVars['ManufacturerID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From productmanufacturer 
            WHERE ManufacturerID = :ManufacturerID");
 
        $sth->bindParam(':ManufacturerID', $ManufacturerID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $ManufacturerID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});
?>