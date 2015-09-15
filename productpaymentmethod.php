<?php 
$app->get('/product/paymentmethod/getbyid/:id', function ($id) {
 
    $app = \Slim\Slim::getInstance();
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("SELECT * 
            FROM productpaymentmethod
            WHERE PaymentMethodID = :id");
 
        $sth->bindParam(':id', $id);
        $sth->execute();
 
        $Method = $sth->fetchAll(PDO::FETCH_OBJ);
 
        if($Method) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Method));
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

$app->get('/product/paymentmethod/get(/)(/:pageno(/:pagelimit))', function ($pageno=0,$pagelimit=20) {
 
    $app = \Slim\Slim::getInstance();
    try 
    {
		$Query="SELECT * FROM productpaymentmethod order by PaymentMethodName ASC";
		
		if($pageno!=0){
		$StartFrom = ($pageno-1) * $pagelimit; 
		$Query.=" LIMIT ". $pagelimit ." OFFSET ". $StartFrom."";
		  }
 
        $sth->execute();
 
        $Method = $sth->fetchAll(PDO::FETCH_OBJ);
 
         if($Method) { 
            $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Record found","document"=> $Method));
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
$app->post('/product/paymentmethod/add(/)', function() use($app) {
 
  
    try 
    {  $allPostVars = $app->request->post();
 
        $db = getDB();

$PaymentMethodName=$allPostVars['PaymentMethodName'];
$PaymentMethodDescription=$allPostVars['PaymentMethodDescription'];

      
		$sth = $db->prepare("SELECT * 
            FROM productpaymentmethod
            WHERE PaymentMethodName = :PaymentMethodName");
 
        $sth->bindParam(':PaymentMethodName', $PaymentMethodName);
		 $sth->execute();
       $color = $sth->fetchAll(PDO::FETCH_OBJ);
		 if($color) {
		  $app->response->setStatus(200);
			$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
            echo json_encode(array("status" => "success", "code" => 1,"message"=> "Status name already exists"));
		 }
		 else{
		$sth = $db->prepare("INSERT INTO productpaymentmethod(PaymentMethodName,PaymentMethodDescription)
            VALUES (:PaymentMethodName,:PaymentMethodDescription)");
        $sth->bindParam(':PaymentMethodName', $PaymentMethodName);
        $sth->bindParam(':PaymentMethodDescription', $PaymentMethodDescription);
		
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
$app->post('/product/paymentmethod/update/', function() use($app) {

    try 
    {
	
    $allPostVars = $app->request->post();
    $PaymentMethodID=$allPostVars['PaymentMethodID'];
	$PaymentMethodName=$allPostVars['PaymentMethodName'];
$PaymentMethodDescription=$allPostVars['PaymentMethodDescription'];	
      
        $db = getDB();
 
        $sth = $db->prepare("UPDATE productpaymentmethod 
            SET PaymentMethodName = :PaymentMethodName, PaymentMethodDescription=:PaymentMethodDescription
            WHERE PaymentMethodID = :PaymentMethodID");
        $sth->bindParam(':PaymentMethodName', $PaymentMethodName);
		$sth->bindParam(':PaymentMethodDescription', $PaymentMethodDescription);
        $sth->bindParam(':PaymentMethodID', $PaymentMethodID);
        $sth->execute();
 
      $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated successfully","id"=> $PaymentMethodID));
		
    } catch(Exception $e) {
        $app->response->setStatus(500);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "error", "code" => 0,"message"=>$e->getMessage()));
    }
	finally {
		 $db = null;
	}
	
 
});

$app->post('/product/paymentmethod/delete/', function() use($app) {
 
    $allPostVars = $app->request->post();
	$PaymentMethodID=$allPostVars['PaymentMethodID'];
 
    try 
    {
        $db = getDB();
 
        $sth = $db->prepare("Delete From productpaymentmethod 
            WHERE PaymentMethodID = :PaymentMethodID");
 
        $sth->bindParam(':PaymentMethodID', $PaymentMethodID);
        $sth->execute();
 
        $app->response->setStatus(200);
		$app->response()->headers('Access-Control-Allow-Origin', '*'); $app->response()->headers->set('Content-Type', 'application/json');
		echo json_encode(array("status" => "success", "code" => 1,"message"=> "Deleted successfully","id"=> $PaymentMethodID));
		
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