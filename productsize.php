<?php 
$app->get('/productsize/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM productsizes
            WHERE SizeID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Size = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Size) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Size));
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

$app->get('/productsize/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    { $db = getDB();
		$Query="SELECT * FROM productsizes order by SizeName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
 
         $sth = $db->prepare($Query);
 

        $sth->execute();
        $Size = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Size) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Size));
        } else {
			$app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 0,"message"=> "No record found"));
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
$app->post('/productsize/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
$SizeName=$allPostVars['SizeName'];
$SizeIsActive=$allPostVars['SizeIsActive'];
$SizeDescription=$allPostVars['SizeDescription'];		
      
		$sth = $db->prepare("SELECT * 
            FROM productsizes
            WHERE SizeName = :SizeName");
 
        $sth->bindParam(':SizeName', $SizeName);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Size name already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO productsizes(SizeName,SizeIsActive,SizeDescription)
            VALUES (:SizeName,:SizeIsActive,:SizeDescription)");
        $sth->bindParam(':SizeName', $SizeName);
        $sth->bindParam(':SizeIsActive', $SizeIsActive);
		$sth->bindParam(':SizeDescription', $SizeDescription);
        $sth->execute();
		$lastInsertId = $db->lastInsertId();
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted Successfully!","id"=> $lastInsertId));
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
$app->post('/productsize/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
    $SizeID=$allPostVars['SizeID'];
$SizeName=$allPostVars['SizeName'];
$SizeIsActive=$allPostVars['SizeIsActive'];
$SizeDescription=$allPostVars['SizeDescription'];		
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE productsizes 
            SET SizeName = :SizeName, SizeDescription=:SizeDescription,SizeIsActive=:SizeIsActive
            WHERE SizeID = :SizeID");
		$sth->bindParam(':SizeName', $SizeName);
        $sth->bindParam(':SizeDescription', $SizeDescription);
		$sth->bindParam(':SizeIsActive', $SizeIsActive);
        $sth->bindParam(':SizeID', $SizeID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $SizeID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/productsize/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$SizeID=$allPostVars['SizeID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From productsizes 
            WHERE SizeID = :SizeID");
 
        $sth->bindParam(':SizeID', $SizeID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $SizeID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});
?>