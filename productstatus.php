<?php 
$app->get('/product/status/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM productstatus
            WHERE StatusID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Status = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Status) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Status));
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

$app->get('/product/status/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM productstatus order by StatusName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
 
        $sth->execute();
 
        $Status = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Status) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Status));
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
$app->post('/product/status/add/', function() use($app) {
 
  
    try 
    {  $allPostVars = $app->request->post();
 
        $db = getDB();

$StatusName=$allPostVars['StatusName'];
$StatusIsActive=$allPostVars['StatusIsActive'];
$StatusDescription=$allPostVars['StatusDescription'];		
      
		$sth = $db->prepare("SELECT * 
            FROM productstatus
            WHERE StatusName = :StatusName");
 
        $sth->bindParam(':StatusName', $StatusName);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Status name already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO productstatus(StatusName,StatusIsActive,StatusDescription)
            VALUES (:StatusName,:StatusIsActive,:StatusDescription)");
        $sth->bindParam(':StatusName', $StatusName);
        $sth->bindParam(':StatusIsActive', $StatusIsActive);
		$sth->bindParam(':StatusDescription', $StatusDescription);
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
$app->post('/product/status/update/', function() use($app) {

    try 
    {
	
    $allPostVars = $app->request->post();
    $StatusID=$allPostVars['StatusID'];
	$StatusName=$allPostVars['StatusName'];
$StatusIsActive=$allPostVars['StatusIsActive'];
$StatusDescription=$allPostVars['StatusDescription'];		
      
        $db = getDB();
 
        $sth = $db->prepare("UPDATE productstatus 
            SET StatusName = :StatusName, StatusIsActive=:StatusIsActive,StatusDescription=:StatusDescription
            WHERE StatusID = :StatusID");
 
       $sth->bindParam(':StatusName', $StatusName);
        $sth->bindParam(':StatusIsActive', $StatusIsActive);
		$sth->bindParam(':StatusDescription', $StatusDescription);
        $sth->bindParam(':StatusID', $StatusID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $StatusID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/product/status/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$StatusID=$allPostVars['StatusID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From productstatus 
            WHERE StatusID = :StatusID");
 
        $sth->bindParam(':StatusID', $StatusID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $StatusID));
		
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