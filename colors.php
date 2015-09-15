<?php 
$app->get('/colors/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM productcolors
            WHERE ColorID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Color = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Color) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Color));
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

$app->get('/colors/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM productcolors order by ColorName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
			$db = getDB();
 
        $sth = $db->prepare($Query);
        $sth->execute();
 
        $Color = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Color) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Color));
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
$app->post('/colors/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
		
		$ColorName=$allPostVars['ColorName'];
		$ColorCode=$allPostVars['ColorCode'];
		
      
		$sth = $db->prepare("SELECT * 
            FROM productcolors
            WHERE ColorName = :ColorName or ColorCode=:ColorCode");
 
        $sth->bindParam(':ColorName', $ColorName);
		$sth->bindParam(':ColorCode', $ColorCode);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Color name/code already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO productcolors(ColorName,ColorCode)
            VALUES (:ColorName,:ColorCode)");
        $sth->bindParam(':ColorName', $ColorName);
        $sth->bindParam(':ColorCode', $ColorCode);
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
$app->post('/colors/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
    $ColorID=$allPostVars['ColorID'];
		$ColorName=$allPostVars['ColorName'];
		$ColorCode=$allPostVars['ColorCode'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE productcolors 
            SET ColorName = :ColorName, ColorCode=:ColorCode
            WHERE ColorID = :ColorID");
 
        $sth->bindParam(':ColorName', $ColorName);
		$sth->bindParam(':ColorCode', $ColorCode);
        $sth->bindParam(':ColorID', $ColorID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $ColorID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/colors/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$ColorID=$allPostVars['ColorID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From productcolors 
            WHERE ColorID = :ColorID");
 
        $sth->bindParam(':ColorID', $ColorID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $ColorID));
		
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