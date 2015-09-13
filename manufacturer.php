<?php 
$app->get('/manufacturer/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM productmanufacturer
            WHERE ManufacturerID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Manufacturer = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Manufacturer) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Manufacturer));
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

$app->get('/manufacturer/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {$db = getDB();
		$Query="SELECT * FROM productmanufacturer order by ManufacturerName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
		$sth = $db->prepare($Query);
 
        $sth->execute();
 
        $Manufacturer = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Manufacturer) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Manufacturer));
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
$app->post('/manufacturer/add/', function() use($app) {
 
    $allPostVars = $app->request->post();
 
    try 
    {
        $db = getDB();
	
		$ManufacturerName=$allPostVars['ManufacturerName'];
		$ManufacturerDescription=$allPostVars['ManufacturerDescription'];
		//$ManufacturerLogo=$allPostVars['ManufacturerLogo'];
		$ManufacturerIsActive=$allPostVars['ManufacturerIsActive'];
		$ManufacturerEmail=$allPostVars['ManufacturerEmail'];
		$ManufacturerPassword=md5($allPostVars['ManufacturerPassword']);
		$ManufacturerMobile=$allPostVars['ManufacturerMobile'];
		$ManufacturerCompanyID=$allPostVars['ManufacturerCompanyID'];
		$ManufacturerLogo="";
		$output_dir = "public/manufacturer-images/";
			if (!is_dir($output_dir)) {
			mkdir($output_dir, 0777, true);       
			}
	$imgs = array();
		if(isset($_FILES['ManufacturerLogo'])){
			$files = $_FILES['ManufacturerLogo'];
		$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$ManufacturerLogo=$name;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
            }
		}
		$sth = $db->prepare("SELECT * 
            FROM productmanufacturer
            WHERE ManufacturerName = :ManufacturerName And ManufacturerCompanyID=:ManufacturerCompanyID");
 
        $sth->bindParam(':ManufacturerName', $ManufacturerName);
		$sth->bindParam(':ManufacturerCompanyID', $ManufacturerCompanyID);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Manufacturer name already exists","image"=>$imgs));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO productmanufacturer(ManufacturerName ,ManufacturerDescription ,ManufacturerLogo ,ManufacturerIsActive ,ManufacturerEmail ,ManufacturerPassword ,ManufacturerMobile ,ManufacturerCompanyID )            VALUES(:ManufacturerName,:ManufacturerDescription,:ManufacturerLogo,:ManufacturerIsActive,:ManufacturerEmail,:ManufacturerPassword,:ManufacturerMobile,:ManufacturerCompanyID)");
			

        $sth->bindParam(':ManufacturerName', $ManufacturerName);
		$sth->bindParam(':ManufacturerCompanyID', $ManufacturerCompanyID);
		$sth->bindParam(':ManufacturerDescription', $ManufacturerDescription);
		$sth->bindParam(':ManufacturerLogo', $ManufacturerLogo);
		$sth->bindParam(':ManufacturerIsActive', $ManufacturerIsActive);
		$sth->bindParam(':ManufacturerEmail', $ManufacturerEmail);
		$sth->bindParam(':ManufacturerPassword', $ManufacturerPassword);
		$sth->bindParam(':ManufacturerMobile', $ManufacturerMobile);
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
$app->post('/manufacturer/update/', function() use($app) {
 
    $allPostVars = $app->request->post();
    $ManufacturerID=$allPostVars['ManufacturerID'];
	$ManufacturerName=$allPostVars['ManufacturerName'];
		$ManufacturerDescription=$allPostVars['ManufacturerDescription'];
		$ManufacturerLogo="";
		$ManufacturerIsActive=$allPostVars['ManufacturerIsActive'];
		$ManufacturerEmail=$allPostVars['ManufacturerEmail'];
		$QueryPassword="";
		if(isset($allPostVars['ManufacturerPassword'])){
		$ManufacturerPassword=md5($allPostVars['ManufacturerPassword']);
		$QueryPassword=" ManufacturerPassword = :ManufacturerPassword,";
		}
		
		$ManufacturerMobile=$allPostVars['ManufacturerMobile'];
		$ManufacturerCompanyID=$allPostVars['ManufacturerCompanyID'];
		$output_dir = "public/manufacturer-images/";
			if (!is_dir($output_dir)) {
			mkdir($output_dir, 0777, true);       
			}
	$imgs = array();
		if(isset($_FILES['ManufacturerLogo'])){
			$files = $_FILES['ManufacturerLogo'];
		$ImageName= str_replace(' ','-',strtolower($files['name']));        
            $ImageExt= substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt= str_replace('.','',$ImageExt);
			$name = 'img'.mt_rand_str(3, 'TUVWXYZ256ABCDEFGH34IJKLMN789OPQR01S').date('Ymd').''.'.'.$ImageExt ;
			$ManufacturerLogo=$name;
            if (move_uploaded_file($files['tmp_name'], $output_dir . $name) === true) {
                $imgs[] = array("status" => "success", "code" => 1, 'url' => $output_dir . $name);
					}
				}
		$QueryLogo="";		
		if($ManufacturerLogo!=""){
		$QueryLogo=" ManufacturerLogo=:ManufacturerLogo,";
		}
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("UPDATE productmanufacturer 
            SET ManufacturerName = :ManufacturerName, ManufacturerCompanyID=:ManufacturerCompanyID,ManufacturerDescription = :ManufacturerDescription, ManufacturerIsActive = :ManufacturerIsActive, ManufacturerEmail=:ManufacturerEmail,".$QueryLogo." ".$QueryPassword." ManufacturerMobile=:ManufacturerMobile
            WHERE ManufacturerID = :ManufacturerID");
 
        $sth->bindParam(':ManufacturerName', $ManufacturerName);
		$sth->bindParam(':ManufacturerCompanyID', $ManufacturerCompanyID);
		$sth->bindParam(':ManufacturerDescription', $ManufacturerDescription);
		if($ManufacturerLogo!=""){
		$sth->bindParam(':ManufacturerLogo', $ManufacturerLogo);
		}
		$sth->bindParam(':ManufacturerIsActive', $ManufacturerIsActive);
		$sth->bindParam(':ManufacturerEmail', $ManufacturerEmail);
		if($QueryPassword!=""){
		$sth->bindParam(':ManufacturerPassword', $ManufacturerPassword);
		}
		$sth->bindParam(':ManufacturerMobile', $ManufacturerMobile);
        $sth->bindParam(':ManufacturerID', $ManufacturerID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $ManufacturerID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/manufacturer/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$ManufacturerID=$allPostVars['ManufacturerID'];
	$ManufacturerIsActive=$allPostVars['ManufacturerIsActive'];
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Update productmanufacturer Set ManufacturerIsActive=:ManufacturerIsActive
            WHERE ManufacturerID = :ManufacturerID");
 
        $sth->bindParam(':ManufacturerID', $ManufacturerID);
		 $sth->bindParam(':ManufacturerIsActive', $ManufacturerIsActive);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $ManufacturerID));
		
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