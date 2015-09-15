<?php 
$app->get('/products/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM products
            WHERE ProductID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Product = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Product) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Product));
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

$app->get('/products/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM products order by ProductCreatedOn DESC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
 
        $sth->execute();
 
        $Product = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Product) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Product));
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
$app->post('/products/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();

		//$ProductID=$allPostVars['ProductID'];
		$CompanyID=$allPostVars['CompanyID'];
		$ShopID=$allPostVars['ShopID'];
		$ProductTitle=$allPostVars['ProductTitle'];
		$ProductDescription=$allPostVars['ProductDescription'];
		$Images=$allPostVars['Images'];
		$Videos=$allPostVars['Videos'];
		$Tags=$allPostVars['Tags'];
		$SellingPrice=$allPostVars['SellingPrice'];
		$CostPrice=$allPostVars['CostPrice'];
		$CategoryID=$allPostVars['CategoryID'];
		$BrandID=$allPostVars['BrandID'];
		$SizeID=$allPostVars['SizeID'];
		$ManufacturersID=$allPostVars['ManufacturersID'];
		$ColorID=$allPostVars['ColorID'];
		$StatusID=$allPostVars['StatusID'];
		$Quantity=$allPostVars['Quantity'];
		$ProductCreatedBy=$allPostVars['ProductCreatedBy'];
		$ProductUpdatedBy=$allPostVars['ProductUpdatedBy'];
		$ProductIsActive=$allPostVars['ProductIsActive'];
		$ProductBardCode=$allPostVars['ProductBardCode'];

		$sth = $db->prepare("SELECT * 
            FROM products
            WHERE ProductTitle = :ProductTitle And CompanyID=:CompanyID");
 
        $sth->bindParam(':ProductTitle', $ProductTitle);
		$sth->bindParam(':CompanyID', $CompanyID);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Product title already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO products(CompanyID ,ShopID ,ProductTitle ,ProductDescription ,Images ,Videos ,Tags ,SellingPrice ,CostPrice ,CategoryID ,BrandID ,SizeID ,ManufacturersID ,ColorID ,StatusID ,Quantity ,ProductCreatedOn ,ProductCreatedBy ,ProductUpdatedOn ,ProductUpdatedBy ,ProductIsActive,ProductBardCode ) VALUES ( :CompanyID ,:ShopID ,:ProductTitle ,:ProductDescription ,:Images ,:Videos ,:Tags ,:SellingPrice ,:CostPrice ,:CategoryID ,:BrandID ,:SizeID ,:ManufacturersID ,:ColorID ,:StatusID ,:Quantity ,:ProductCreatedOn ,:ProductCreatedBy ,:ProductUpdatedOn ,:ProductUpdatedBy ,:ProductIsActive,:ProductBardCode)");
			

       //$sth->bindParam(':ProductID', $ProductID);
		$sth->bindParam(':CompanyID	', $CompanyID);							
		$sth->bindParam(':ShopID', $ShopID);								
		$sth->bindParam(':ProductTitle', $ProductTitle);								
		$sth->bindParam(':ProductDescription', $ProductDescription);								
		$sth->bindParam(':Images', $Images);								
		$sth->bindParam(':Videos', $Videos);								
		$sth->bindParam(':Tags', $Tags);								
		$sth->bindParam(':SellingPrice', $SellingPrice);								
		$sth->bindParam(':CostPrice', $CostPrice);								
		$sth->bindParam(':CategoryID', $CategoryID);								
		$sth->bindParam(':BrandID', $BrandID);								
		$sth->bindParam(':SizeID', $SizeID);								
		$sth->bindParam(':ManufacturersID', $ManufacturersID);								
		$sth->bindParam(':ColorID', $ColorID);								
		$sth->bindParam(':StatusID', $StatusID);								
		$sth->bindParam(':Quantity', $Quantity);								
		//$sth->bindParam(':ProductCreatedOn', $ProductCreatedOn);								
		$sth->bindParam(':ProductCreatedBy', $ProductCreatedBy);								
		//$sth->bindParam(':ProductUpdatedOn', $ProductUpdatedOn);								
		$sth->bindParam(':ProductUpdatedBy', $ProductUpdatedBy);								
		$sth->bindParam(':ProductIsActive', $ProductIsActive);
		$sth->bindParam(':ProductBardCode', $ProductBardCode);
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
$app->post('/products/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
    $ProductID=$allPostVars['ProductID'];
		$CompanyID=$allPostVars['CompanyID'];
		$ShopID=$allPostVars['ShopID'];
		$ProductTitle=$allPostVars['ProductTitle'];
		$ProductDescription=$allPostVars['ProductDescription'];
		$Images=$allPostVars['Images'];
		$Videos=$allPostVars['Videos'];
		$Tags=$allPostVars['Tags'];
		$SellingPrice=$allPostVars['SellingPrice'];
		$CostPrice=$allPostVars['CostPrice'];
		$CategoryID=$allPostVars['CategoryID'];
		$BrandID=$allPostVars['BrandID'];
		$SizeID=$allPostVars['SizeID'];
		$ManufacturersID=$allPostVars['ManufacturersID'];
		$ColorID=$allPostVars['ColorID'];
		$StatusID=$allPostVars['StatusID'];
		$Quantity=$allPostVars['Quantity'];
		//$ProductCreatedBy=$allPostVars['ProductCreatedBy'];
		$ProductUpdatedBy=$allPostVars['ProductUpdatedBy'];
		$ProductIsActive=$allPostVars['ProductIsActive'];
		$ProductBardCode=$allPostVars['ProductBardCode'];
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE products 
            SET CompanyID= :CompanyID, ShopID= :ShopID, ProductTitle= :ProductTitle, ProductDescription= :ProductDescription, Images= :Images, Videos= :Videos, Tags= :Tags, SellingPrice= :SellingPrice, CostPrice= :CostPrice, CategoryID= :CategoryID, BrandID= :BrandID, SizeID= :SizeID, ManufacturersID= :ManufacturersID, ColorID= :ColorID, StatusID= :StatusID, Quantity= :Quantity,   ProductUpdatedBy= :ProductUpdatedBy, ProductIsActive= :ProductIsActive,ProductBardCode=:ProductBardCode
            WHERE ProductID = :ProductID");
 
       $sth->bindParam(':CompanyID	', $CompanyID);							
		$sth->bindParam(':ShopID', $ShopID);								
		$sth->bindParam(':ProductTitle', $ProductTitle);								
		$sth->bindParam(':ProductDescription', $ProductDescription);								
		$sth->bindParam(':Images', $Images);								
		$sth->bindParam(':Videos', $Videos);								
		$sth->bindParam(':Tags', $Tags);								
		$sth->bindParam(':SellingPrice', $SellingPrice);								
		$sth->bindParam(':CostPrice', $CostPrice);								
		$sth->bindParam(':CategoryID', $CategoryID);								
		$sth->bindParam(':BrandID', $BrandID);								
		$sth->bindParam(':SizeID', $SizeID);								
		$sth->bindParam(':ManufacturersID', $ManufacturersID);								
		$sth->bindParam(':ColorID', $ColorID);								
		$sth->bindParam(':StatusID', $StatusID);								
		$sth->bindParam(':Quantity', $Quantity);								
		//$sth->bindParam(':ProductCreatedOn', $ProductCreatedOn);								
		//$sth->bindParam(':ProductCreatedBy', $ProductCreatedBy);								
		//$sth->bindParam(':ProductUpdatedOn', $ProductUpdatedOn);								
		$sth->bindParam(':ProductUpdatedBy', $ProductUpdatedBy);								
		$sth->bindParam(':ProductIsActive', $ProductIsActive);
        $sth->bindParam(':ProductID', $ProductID);
		$sth->bindParam(':ProductBardCode', $ProductBardCode);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $ProductID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/products/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$ProductID=$allPostVars['ProductID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From products 
            WHERE ProductID = :ProductID");
 
        $sth->bindParam(':ProductID', $ProductID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $ProductID));
		
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