<?php 
$app->get('/manufacture/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM productmanufacture
            WHERE ManufactureID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Manufacture = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Manufacture) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Manufacture));
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

$app->get('/manufacture/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM productmanufacture order by ManufactureName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
 
        $sth->execute();
 
        $Manufacture = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Manufacture) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Manufacture));
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
$app->post('/manufacture/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
	
		$ManufactureName=$allPostVars['ManufactureName'];
		$ManufactureDescription=$allPostVars['ManufactureDescription'];
		$ManufactureLogo=$allPostVars['ManufactureLogo'];
		$ManufactureIsActive=$allPostVars['ManufactureIsActive'];
		$ManufactureEmail=$allPostVars['ManufactureEmail'];
		$ManufacturePassword=$allPostVars['ManufacturePassword'];
		$ManufactureMobile=$allPostVars['ManufactureMobile'];
		$ManufactureCompanyID=$allPostVars['ManufactureCompanyID'];
		
		$sth = $db->prepare("SELECT * 
            FROM productmanufacture
            WHERE ManufactureName = :ManufactureName And ManufactureCompanyID=:ManufactureCompanyID");
 
        $sth->bindParam(':ManufactureName', $ManufactureName);
		$sth->bindParam(':ManufactureCompanyID', $ManufactureCompanyID);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Manufacture name already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO productmanufacture(ManufactureName ,ManufactureDescription ,ManufactureLogo ,ManufactureIsActive ,ManufactureEmail ,ManufacturePassword ,ManufactureMobile ,ManufactureCompanyID )            VALUES(:ManufactureName,:ManufactureDescription,:ManufactureLogo,:ManufactureIsActive,:ManufactureEmail,:ManufacturePassword,:ManufactureMobile,:ManufactureCompanyID)");
			

        $sth->bindParam(':ManufactureName', $ManufactureName);
		$sth->bindParam(':ManufactureCompanyID', $ManufactureCompanyID);
		$sth->bindParam(':ManufactureDescription', $ManufactureDescription);
		$sth->bindParam(':ManufactureLogo', $ManufactureLogo);
		$sth->bindParam(':ManufactureIsActive', $ManufactureIsActive);
		$sth->bindParam(':ManufactureEmail', $ManufactureEmail);
		$sth->bindParam(':ManufacturePassword', $ManufacturePassword);
		$sth->bindParam(':ManufactureMobile', $ManufactureMobile);
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
$app->post('/manufacture/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
    $ManufactureID=$allPostVars['ManufactureID'];
	$ManufactureName=$allPostVars['ManufactureName'];
		$ManufactureDescription=$allPostVars['ManufactureDescription'];
		$ManufactureLogo=$allPostVars['ManufactureLogo'];
		$ManufactureIsActive=$allPostVars['ManufactureIsActive'];
		$ManufactureEmail=$allPostVars['ManufactureEmail'];
		$ManufacturePassword=$allPostVars['ManufacturePassword'];
		$ManufactureMobile=$allPostVars['ManufactureMobile'];
		$ManufactureCompanyID=$allPostVars['ManufactureCompanyID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE productmanufacture 
            SET ManufactureName = :ManufactureName, ManufactureCompanyID=:ManufactureCompanyID,ManufactureDescription = :ManufactureDescription, ManufactureLogo=:ManufactureLogo,ManufactureIsActive = :ManufactureIsActive, ManufactureEmail=:ManufactureEmail,ManufacturePassword = :ManufacturePassword, ManufactureMobile=:ManufactureMobile
            WHERE ManufactureID = :ManufactureID");
 
        $sth->bindParam(':ManufactureName', $ManufactureName);
		$sth->bindParam(':ManufactureCompanyID', $ManufactureCompanyID);
		$sth->bindParam(':ManufactureDescription', $ManufactureDescription);
		$sth->bindParam(':ManufactureLogo', $ManufactureLogo);
		$sth->bindParam(':ManufactureIsActive', $ManufactureIsActive);
		$sth->bindParam(':ManufactureEmail', $ManufactureEmail);
		$sth->bindParam(':ManufacturePassword', $ManufacturePassword);
		$sth->bindParam(':ManufactureMobile', $ManufactureMobile);
        $sth->bindParam(':ManufactureID', $ManufactureID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $ManufactureID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/manufacture/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$ManufactureID=$allPostVars['ManufactureID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From productmanufacture 
            WHERE ManufactureID = :ManufactureID");
 
        $sth->bindParam(':ManufactureID', $ManufactureID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $ManufactureID));
		
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