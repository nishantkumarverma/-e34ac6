<?php 
$app->get('/productbrands/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM productbrands
            WHERE BrandID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Brand = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Brand) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Brand));
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

$app->get('/productbrands/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM productbrands order by BrandName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
 
        $sth->execute();
 
        $Brand = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Brand) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Brand));
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
$app->post('/productbrands/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
	
		$BrandName=$allPostVars['BrandName'];
		$BrandLogo=$allPostVars['BrandLogo'];
		$BrandIsActive=$allPostVars['BrandIsActive'];
		$BrandDescription=$allPostVars['BrandDescription'];
		$sth = $db->prepare("SELECT * 
            FROM productbrands
            WHERE BrandName = :BrandName");
 
        $sth->bindParam(':BrandName', $BrandName);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Brand name already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO productbrands(BrandName,BrandLogo,BrandIsActive,BrandDescription)
            VALUES (:BrandName,:BrandLogo,:BrandIsActive,:BrandDescription)");
        $sth->bindParam(':BrandName', $BrandName);
        $sth->bindParam(':BrandLogo', $BrandLogo);
		$sth->bindParam(':BrandIsActive', $BrandIsActive);
		$sth->bindParam(':BrandDescription', $BrandDescription);
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
$app->post('/productbrands/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
    $BrandID=$allPostVars['BrandID'];
	$BrandName=$allPostVars['BrandName'];
	$BrandLogo=$allPostVars['BrandLogo'];
	$BrandIsActive=$allPostVars['BrandIsActive'];
	$BrandDescription=$allPostVars['BrandDescription'];	
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE productbrands 
            SET BrandName = :BrandName, BrandLogo=:BrandLogo,BrandIsActive=:BrandIsActive,BrandDescription=:BrandDescription
            WHERE BrandID = :BrandID");
		$sth->bindParam(':BrandName', $BrandName);
        $sth->bindParam(':BrandLogo', $BrandLogo);
		$sth->bindParam(':BrandIsActive', $BrandIsActive);
		$sth->bindParam(':BrandDescription', $BrandDescription);
        $sth->bindParam(':BrandID', $BrandID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $BrandID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/productbrands/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$BrandID=$allPostVars['BrandID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From productbrands 
            WHERE BrandID = :BrandID");
 
        $sth->bindParam(':BrandID', $BrandID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $BrandID));
		
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