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
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Brand));
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

$app->get('/productbrands/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM productbrands order by BrandName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
		   $db = getDB();
		$sth = $db->prepare($Query);
        $sth->execute();
 
        $Brand = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Brand) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Brand));
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
$app->post('/productbrands/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
	
		$BrandName=$allPostVars['BrandName'];
		$BrandLogo="";
		$BrandIsActive=$allPostVars['BrandIsActive'];
		$BrandDescription=$allPostVars['BrandDescription'];
		
		$output_dir = "public/brand-logo/";
			if (!is_dir($output_dir)) {
			mkdir($output_dir, 0777, true);       
			}
			
			$imgs = array();
		
		$sth = $db->prepare("SELECT * 
            FROM productbrands
            WHERE BrandName = :BrandName");
 
        $sth->bindParam(':BrandName', $BrandName);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Brand name already exists"));
		 }
		 else{
		 if(isset($_FILES['BrandLogo'])){
			$files = $_FILES['BrandLogo'];
		$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$BrandLogo=$name;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
            }
		}
		$sth = $db->prepare("INSERT INTO productbrands(BrandName,BrandLogo,BrandIsActive,BrandDescription)
            VALUES (:BrandName,:BrandLogo,:BrandIsActive,:BrandDescription)");
        $sth->bindParam(':BrandName', $BrandName);
        $sth->bindParam(':BrandLogo', $BrandLogo);
		$sth->bindParam(':BrandIsActive', $BrandIsActive);
		$sth->bindParam(':BrandDescription', $BrandDescription);
        $sth->execute();
		$lastInsertId = $db->lastInsertId();
		$app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted Successfully!","id"=> $lastInsertId,"image"=>$imgs));
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
$app->post('/productbrands/update/', function() use($app) {
 
    
    try 
    {
		$allPostVars = $app->request->post();
		$BrandID=$allPostVars['BrandID'];
		$BrandName=$allPostVars['BrandName'];
		$BrandLogo="";
		$BrandIsActive=$allPostVars['BrandIsActive'];
		$BrandDescription=$allPostVars['BrandDescription'];	
		$output_dir = "public/brand-logo/";
				if (!is_dir($output_dir)) {
				mkdir($output_dir, 0777, true);       
				}
        $db = getDB();
		$imgs = array();
		if(isset($_FILES['BrandLogo'])){
			$files = $_FILES['BrandLogo'];
		$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$BrandLogo=$name;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
					}
				}
		$QueryLogo="";		
		if($BrandLogo!=""){
		$QueryLogo=" BrandLogo=:BrandLogo,";
		}
        $sth = $db->prepare("UPDATE productbrands 
            SET BrandName = :BrandName,".$QueryLogo."BrandIsActive=:BrandIsActive,BrandDescription=:BrandDescription
            WHERE BrandID = :BrandID");
		$sth->bindParam(':BrandName', $BrandName);
		if($BrandLogo!=""){
        $sth->bindParam(':BrandLogo', $BrandLogo);
		}
		$sth->bindParam(':BrandIsActive', $BrandIsActive);
		$sth->bindParam(':BrandDescription', $BrandDescription);
        $sth->bindParam(':BrandID', $BrandID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $BrandID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/productbrands/delete/', function() use($app) {
 
   
    try 
    {
	 $allPostVars = $app->request->post();
	$BrandID=$allPostVars['BrandID'];
	$BrandIsActive=$allPostVars['BrandIsActive'];
        $db = getDB();
 
		$sth = $db->prepare("Update productbrands Set BrandIsActive=:BrandIsActive
            WHERE BrandID = :BrandID");
       // $sth = $db->prepare("Delete From productbrands WHERE BrandID = :BrandID");
 
        $sth->bindParam(':BrandID', $BrandID);
		$sth->bindParam(':BrandIsActive', $BrandIsActive);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $BrandID));
		
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