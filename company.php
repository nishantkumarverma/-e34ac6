<?php 
$app->get('/company/getbyid/:id', function ($id) {
    try 
    {	
		$app = \Slim\Slim::getInstance();
		$db = getDB();
        $sth = $db->prepare("SELECT * 
            FROM company
            WHERE CompanyID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $company = $sth->fetchAll(PDO::FETCH_OBJ);
		if($company) { 
			$app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $company));
   
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

$app->get('/company/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) { 
    try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$Query="SELECT * FROM company";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		 }
 
        $sth = $db->prepare($Query);
		$sth->execute();
        $company = $sth->fetchAll(PDO::FETCH_OBJ);

        if($company) { 
            $app->response->setStatus(200);
			$app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $company));
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

$app->post('/company/add/', function() use($app) {
	try 
    {	$app = \Slim\Slim::getInstance();
		$db = getDB();
		$allPostVars = $app->request->post();
	
		$CompanyEmail = $allPostVars['CompanyEmail'];
		$CompanyPassword = $allPostVars['CompanyPassword'];
		$CompanyContactPerson = $allPostVars['CompanyContactPerson'];
		$CompanyMobile = $allPostVars['CompanyMobile'];
		$CompanyName = $allPostVars['CompanyName'];
		//$CompanyLogo = $allPostVars['CompanyLogo'];
		$CompanyIsActive = $allPostVars['CompanyIsActive'];
		$CompanyPublicKey = uniqid();
		$CompanyDescription = $allPostVars['CompanyDescription'];

		$qry="SELECT * FROM company WHERE CompanyName='".$CompanyName."'";
		$sth = $db->prepare($qry);
		$sth->execute();
        $company = $sth->fetchAll(PDO::FETCH_OBJ);
		
		if($company){
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>"company name already exits"));
		}else{		
		
		$sth = $db->prepare("INSERT INTO company (CompanyEmail,CompanyPassword,CompanyContactPerson,CompanyMobile,CompanyName,CompanyIsActive,CompanyPublicKey,CompanyDescription) VALUES (:CompanyEmail,:CompanyPassword,:CompanyContactPerson,:CompanyMobile,:CompanyName,:CompanyIsActive,:CompanyPublicKey,:CompanyDescription)");
		$sth->bindParam(':CompanyEmail', $CompanyEmail);
		$sth->bindParam(':CompanyPassword', $CompanyPassword);
		$sth->bindParam(':CompanyContactPerson', $CompanyContactPerson);
		$sth->bindParam(':CompanyMobile', $CompanyMobile);
		$sth->bindParam(':CompanyName', $CompanyName);
		$sth->bindParam(':CompanyIsActive', $CompanyIsActive);
		$sth->bindParam(':CompanyPublicKey', $CompanyPublicKey);
		$sth->bindParam(':CompanyDescription', $CompanyDescription);
		$sth->execute();
		
		$lastInsertedID = $db->lastInsertID();
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Inserted successfully","id"=> $lastInsertedID));
		}
    } catch(Exception $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
 
});

$app->post('/company/update/', function() use($app) {
	try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		
		$CompanyID = $allPostVars['CompanyID'];
		$CompanyEmail = $allPostVars['CompanyEmail'];
		$CompanyPassword = $allPostVars['CompanyPassword'];
		$CompanyContactPerson = $allPostVars['CompanyContactPerson'];
		$CompanyMobile = $allPostVars['CompanyMobile'];
		$CompanyName = $allPostVars['CompanyName'];
		//$CompanyLogo = $allPostVars['CompanyLogo'];
		$CompanyIsActive = $allPostVars['CompanyIsActive'];
		$CompanyPublicKey = uniqid();
		$CompanyDescription = $allPostVars['CompanyDescription'];
		
        $db = getDB();
		$sth = $db->prepare("UPDATE states SET CompanyEmail=:CompanyEmail, CompanyPassword=:CompanyPassword,CompanyContactPerson=:CompanyContactPerson, CompanyName=:CompanyName,CompanyIsActive=:CompanyIsActive, CompanyPublicKey=:CompanyPublicKey,CompanyDescription=:CompanyDescription WHERE CompanyID = :CompanyID");
		
		$sth->bindParam(':CompanyID', $CompanyID);
		$sth->bindParam(':CompanyEmail', $CompanyEmail);
		$sth->bindParam(':CompanyPassword', $CompanyPassword);
		$sth->bindParam(':CompanyContactPerson', $CompanyContactPerson);
		$sth->bindParam(':CompanyMobile', $CompanyMobile);
		$sth->bindParam(':CompanyName', $CompanyName);
		$sth->bindParam(':CompanyIsActive', $CompanyIsActive);
		$sth->bindParam(':CompanyPublicKey', $CompanyPublicKey);
		$sth->bindParam(':CompanyDescription', $CompanyDescription);
		$sth->execute();

		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","CompanyID"=> $CompanyID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
});

$app->post('/company/delete/', function() use($app) { 
    try 
    {
		$app = \Slim\Slim::getInstance();
		$allPostVars = $app->request->post();
		$CompanyID=$allPostVars['CompanyID'];
		
        $db = getDB();
        $sth = $db->prepare("Delete From company 
            WHERE CompanyID = :CompanyID");
 
        $sth->bindParam(':CompanyID', $CompanyID);
        $sth->execute();
		
		
		$app->response->setStatus(200);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully"));
		
    } catch(PDOException $e) {
		$app->response->setStatus(500);
		$app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		$db = null;
	}
 
});

?>