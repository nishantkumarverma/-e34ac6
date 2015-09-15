<?php 
$app->get('/company/shops/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM company_shop
            WHERE ShopID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Shop = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Shop) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Shop));
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

$app->get('/company/shops/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM company_shop order by ShopName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
 
        $sth->execute();
 
        $Shop = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Shop) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Shop));
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
$app->post('/company/shops/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();

		$ShopPincode=$allPostVars['ShopPincode'];
		$ShopName=$allPostVars['ShopName'];
		$ShopLongitude=$allPostVars['ShopLongitude'];
		$ShopLatitude=$allPostVars['ShopLatitude'];
		$ShopIsActive=$allPostVars['ShopIsActive'];
		//$ShopID=$allPostVars['ShopID'];
		$ShopFullAddress=$allPostVars['ShopFullAddress'];
		$ShopCompanyID=$allPostVars['ShopCompanyID'];
		$ShopCityID=$allPostVars['ShopCityID'];	
		$sth = $db->prepare("SELECT * 
            FROM company_shop
            WHERE ShopName = :ShopName And ShopCompanyID=:ShopCompanyID");
 
        $sth->bindParam(':ShopName', $ShopName);
		$sth->bindParam(':ShopCompanyID', $ShopCompanyID);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Entity name already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO company_shop(ShopCompanyID ,ShopName ,ShopFullAddress ,ShopPincode ,ShopLatitude ,ShopLongitude ,ShopCityID ,ShopIsActive ) VALUES(:ShopCompanyID,:ShopName,:ShopFullAddress,:ShopLatitude,:ShopLongitude,:ShopCityID,:ShopIsActive)");
        $sth->bindParam(':ShopCompanyID', $ShopCompanyID);
		$sth->bindParam(':ShopName', $ShopName);
		$sth->bindParam(':ShopPincode', $ShopPincode);
		$sth->bindParam(':ShopFullAddress', $ShopFullAddress);
		$sth->bindParam(':ShopLatitude', $ShopLatitude);
		$sth->bindParam(':ShopLongitude', $ShopLongitude);
		$sth->bindParam(':ShopCityID', $ShopCityID);
		$sth->bindParam(':ShopIsActive', $ShopIsActive);
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
$app->post('/company/shops/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
    $ShopID=$allPostVars['ShopID'];
	$ShopPincode=$allPostVars['ShopPincode'];
	$ShopName=$allPostVars['ShopName'];
	$ShopLongitude=$allPostVars['ShopLongitude'];
	$ShopLatitude=$allPostVars['ShopLatitude'];
	$ShopIsActive=$allPostVars['ShopIsActive'];
	$ShopFullAddress=$allPostVars['ShopFullAddress'];
	$ShopCompanyID=$allPostVars['ShopCompanyID'];
	$ShopCityID=$allPostVars['ShopCityID'];	
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE company_shop 
            SET ShopPincode = :ShopPincode, ShopPincode=:ShopPincode,ShopName = :ShopName, ShopLongitude=:ShopLongitude,ShopLatitude = :ShopLatitude, ShopIsActive=:ShopIsActive,ShopFullAddress = :ShopFullAddress, ShopCompanyID=:ShopCompanyID,ShopCityID=:ShopCityID,
            WHERE ShopID = :ShopID");
 
        $sth->bindParam(':ShopPincode', $ShopPincode);
		$sth->bindParam(':ShopPincode', $ShopPincode);
		$sth->bindParam(':ShopName', $ShopName);
		$sth->bindParam(':ShopLongitude', $ShopLongitude);
		$sth->bindParam(':ShopLatitude', $ShopLatitude);
		$sth->bindParam(':ShopIsActive', $ShopIsActive);
		$sth->bindParam(':ShopFullAddress', $ShopFullAddress);
		$sth->bindParam(':ShopCityID', $ShopCityID);
        $sth->bindParam(':ShopID', $ShopID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $ShopID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/company/shops/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$ShopID=$allPostVars['ShopID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From company_shop 
            WHERE ShopID = :ShopID");
 
        $sth->bindParam(':ShopID', $ShopID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $ShopID));
		
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