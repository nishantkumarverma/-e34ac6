<?php 
$app->get('/product/customfields/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM productcustomfields
            WHERE CustomFieldID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $CustomField = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($CustomField) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $CustomField));
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

$app->get('/product/customfields/getbyproduct/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM productcustomfields
            WHERE CustomFieldProductID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $CustomField = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($CustomField) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $CustomField));
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
$app->get('/product/customfields/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM productcustomfields order by CustomFieldName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
 
        $sth->execute();
 
        $CustomField = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($CustomField) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $CustomField));
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
$app->post('/product/customfields/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();

//$CustomFieldID=$allPostVars['CustomFieldID'];
$CustomFieldName=$allPostVars['CustomFieldName'];
$CustomFieldDataTypeID=$allPostVars['CustomFieldDataTypeID'];
$CustomFieldValue=$allPostVars['CustomFieldValue'];
$CustomFieldProductID=$allPostVars['CustomFieldProductID'];
		$sth = $db->prepare("SELECT * 
            FROM productcustomfields
            WHERE CustomFieldName = :CustomFieldName and CustomFieldProductID=:CustomFieldProductID");
 
        $sth->bindParam(':CustomFieldName', $CustomFieldName);
		$sth->bindParam(':CustomFieldProductID', $CustomFieldProductID);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Custom field name already exists for this product"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO productcustomfields(CustomFieldName,CustomFieldDataTypeID,CustomFieldValue,CustomFieldProductID)
            VALUES (:CustomFieldName,:CustomFieldDataTypeID,:CustomFieldValue,:CustomFieldProductID)");
        $sth->bindParam(':CustomFieldName', $CustomFieldName);
        $sth->bindParam(':CustomFieldDataTypeID', $CustomFieldDataTypeID);
		$sth->bindParam(':CustomFieldValue', $CustomFieldValue);
		$sth->bindParam(':CustomFieldProductID', $CustomFieldProductID);
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
$app->post('/product/customfields/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
$CustomFieldID=$allPostVars['CustomFieldID'];
$CustomFieldName=$allPostVars['CustomFieldName'];
$CustomFieldDataTypeID=$allPostVars['CustomFieldDataTypeID'];
$CustomFieldValue=$allPostVars['CustomFieldValue'];
$CustomFieldProductID=$allPostVars['CustomFieldProductID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE productcustomfields 
            SET CustomFieldName = :CustomFieldName, CustomFieldName=:CustomFieldName,CustomFieldDataTypeID=:CustomFieldDataTypeID,CustomFieldValue=:CustomFieldValue,CustomFieldProductID=:CustomFieldProductID WHERE CustomFieldID = :CustomFieldID");
		$sth->bindParam(':CustomFieldName', $CustomFieldName);
        $sth->bindParam(':CustomFieldName', $CustomFieldName);
		$sth->bindParam(':CustomFieldDataTypeID', $CustomFieldDataTypeID);
		$sth->bindParam(':CustomFieldValue', $CustomFieldValue);
		$sth->bindParam(':CustomFieldProductID', $CustomFieldProductID);
        $sth->bindParam(':CustomFieldID', $CustomFieldID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $CustomFieldID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/product/customfields/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$CustomFieldID=$allPostVars['CustomFieldID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From productcustomfields 
            WHERE CustomFieldID = :CustomFieldID");
 
        $sth->bindParam(':CustomFieldID', $CustomFieldID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $CustomFieldID));
		
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