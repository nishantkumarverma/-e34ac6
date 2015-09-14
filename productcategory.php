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
            echo json_encode(array("status" => "success", "code" => 0,"message"=> "No record found"));
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
    { $db = getDB();
		$Query="SELECT * FROM productcategory order by CategoryName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
		$sth = $db->prepare($Query);
        $sth->execute();
 
        $Category = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Category) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Category));
        } else {
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 0,"message"=> "No record found"));
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
		$CategoryLogo="";
		$CategoryIsActive=$allPostVars['CategoryIsActive'];		
		$output_dir = "public/category-images/";
		if (!is_dir($output_dir)) {
			mkdir($output_dir, 0777, true);       
			}
	$imgs = array();
		$sth = $db->prepare("SELECT * 
            FROM productcategory
            WHERE CategoryCompanyID = :CategoryCompanyID And CategoryName=:CategoryName");
 
        $sth->bindParam(':CategoryCompanyID', $CategoryCompanyID);
		$sth->bindParam(':CategoryName', $CategoryName);
		 $sth->execute();
       $Category = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($Category) {
		  $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 0,"message"=> "Category name already exists"));
		 }
		 else{
		 $imgs = array();
		 if(isset($_FILES['CategoryLogo'])){
			$files = $_FILES['CategoryLogo'];
		$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$CategoryLogo=$name;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
            }
		}
		$sth = $db->prepare("INSERT INTO productcategory(CategoryCompanyID ,CategoryName ,CategoryDescription ,CategoryLogo ,CategoryIsActive )            VALUES(:CategoryCompanyID,:CategoryName,:CategoryDescription,:CategoryLogo,:CategoryIsActive)");
			
        $sth->bindParam(':CategoryCompanyID', $CategoryCompanyID);
		$sth->bindParam(':CategoryName', $CategoryName);
		$sth->bindParam(':CategoryDescription', $CategoryDescription);
		$sth->bindParam(':CategoryLogo', $CategoryLogo);
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
 
    
 
    try 
    {
		$allPostVars = $app->request->post();
		$CategoryID=$allPostVars['CategoryID'];
		$CategoryCompanyID=$allPostVars['CategoryCompanyID'];
		$CategoryName=$allPostVars['CategoryName'];
		$CategoryDescription=$allPostVars['CategoryDescription'];
		$CategoryLogo="";
		$CategoryIsActive=$allPostVars['CategoryIsActive'];
        $db = getDB();
		$output_dir = "public/category-images/";
			if (!is_dir($output_dir)) {
			mkdir($output_dir, 0777, true);       
			}
		$imgs = array();
		if(isset($_FILES['CategoryLogo'])){
			$files = $_FILES['CategoryLogo'];
		$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$CategoryLogo=$name;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
					}
				}
		$QueryLogo="";		
		if($CategoryLogo!=""){
		$QueryLogo=" CategoryLogo=:CategoryLogo,";
		}
        $sth = $db->prepare("UPDATE productcategory 
            SET CategoryCompanyID = :CategoryCompanyID, CategoryName=:CategoryName,CategoryDescription=:CategoryDescription,".$QueryLogo." CategoryIsActive=:CategoryIsActive
            WHERE CategoryID = :CategoryID");
 
        $sth->bindParam(':CategoryCompanyID', $CategoryCompanyID);
		$sth->bindParam(':CategoryName', $CategoryName);
		$sth->bindParam(':CategoryDescription', $CategoryDescription);
		if($CategoryLogo!=""){
		$sth->bindParam(':CategoryLogo', $CategoryLogo);
		}
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
	$CategoryIsActive=$allPostVars['CategoryIsActive'];
 
    try 
    {
        $db = getDB();
 
		$sth = $db->prepare("Update productcategory Set CategoryIsActive=:CategoryIsActive
            WHERE CategoryID = :CategoryID");
        $sth->bindParam(':CategoryID', $CategoryID);
		$sth->bindParam(':CategoryIsActive', $CategoryIsActive);
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