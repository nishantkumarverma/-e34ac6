<?php 
$app->get('/product/category/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM productcategory
            WHERE CategoryID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Category = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Category) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Category));
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

$app->get('/product/category/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM productcategory order by CategoryName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
 
        $sth->execute();
 
        $Category = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Category) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Category));
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
$app->post('/product/category/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
	
		$CategoryCompanyID=$allPostVars['CategoryCompanyID'];
		$CategoryName=$allPostVars['CategoryName'];
		$CategoryDescription=$allPostVars['CategoryDescription'];
		$CategoryImage=$allPostVars['CategoryImage'];
		$CategoryIsActive=$allPostVars['CategoryIsActive'];		

		$sth = $db->prepare("SELECT * 
            FROM productcategory
            WHERE CategoryCompanyID = :CategoryCompanyID And CategoryName=:CategoryName");
 
        $sth->bindParam(':CategoryCompanyID', $CategoryCompanyID);
		$sth->bindParam(':CategoryName', $CategoryName);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Category name already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO productcategory(CategoryCompanyID ,CategoryName ,CategoryDescription ,CategoryImage ,CategoryIsActive )            VALUES(:CategoryCompanyID,:CategoryName,:CategoryDescription,:CategoryImage,:CategoryIsActive)");
			
        $sth->bindParam(':CategoryCompanyID', $CategoryCompanyID);
		$sth->bindParam(':CategoryName', $CategoryName);
		$sth->bindParam(':CategoryDescription', $CategoryDescription);
		$sth->bindParam(':CategoryImage', $CategoryImage);
		$sth->bindParam(':CategoryIsActive', $CategoryIsActive);
	
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
$app->post('/product/category/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
    $CategoryID=$allPostVars['CategoryID'];
$CategoryCompanyID=$allPostVars['CategoryCompanyID'];
$CategoryName=$allPostVars['CategoryName'];
$CategoryDescription=$allPostVars['CategoryDescription'];
$CategoryImage=$allPostVars['CategoryImage'];
$CategoryIsActive=$allPostVars['CategoryIsActive'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE productcategory 
            SET CategoryCompanyID = :CategoryCompanyID, CategoryName=:CategoryName,CategoryName = :CategoryName, CategoryDescription=:CategoryDescription,CategoryImage = :CategoryImage, CategoryIsActive=:CategoryIsActive
            WHERE CategoryID = :CategoryID");
 
        $sth->bindParam(':CategoryCompanyID', $CategoryCompanyID);
		$sth->bindParam(':CategoryName', $CategoryName);
		$sth->bindParam(':CategoryName', $CategoryName);
		$sth->bindParam(':CategoryDescription', $CategoryDescription);
		$sth->bindParam(':CategoryImage', $CategoryImage);
		$sth->bindParam(':CategoryIsActive', $CategoryIsActive);
        $sth->bindParam(':CategoryID', $CategoryID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $CategoryID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/product/category/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$CategoryID=$allPostVars['CategoryID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From productcategory 
            WHERE CategoryID = :CategoryID");
 
        $sth->bindParam(':CategoryID', $CategoryID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $CategoryID));
		
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