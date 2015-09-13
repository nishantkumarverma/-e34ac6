<?php


require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

function getDB()
{
    $dbhost = "localhost";
    $dbuser = "nkvMeetingo";
    $dbpass = "nkvMeetingo";
    $dbname = "yembrance";
 
    $mysql_conn_string = "mysql:host=$dbhost;dbname=$dbname";
    $dbConnection = new PDO($mysql_conn_string, $dbuser, $dbpass); 
    $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConnection;
}
 
function getMongoDB()
{
    $connection=new Mongo();
	$db = $connection->yembrace;

    return $db;
}
 
$app->get('/', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API";
}); 

$app->get('/address_users', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - address_users";
}); 

$app->get('/beacons_company', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - beacons_company";
});

$app->get('/beacons_master', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - beacons_master";
});

$app->get('/city', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - city";
});

$app->get('/company', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - company";
});

$app->get('/company_shop', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - company_shop";
}); 

$app->get('/country', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - Country";
}); 

$app->get('/product_brands', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - product_brands";
}); 

$app->get('/product_category', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - product_category";
}); 

$app->get('/product_colors', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - product_colors";
}); 

$app->get('/product_manufacture', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - product_manufacture";
}); 

$app->get('/product_paymentmethod', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - product_paymentmethod";
}); 

$app->get('/product_sizes', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - product_sizes";
}); 

$app->get('/product_status', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - product_status";
}); 

$app->get('/products', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - products";
}); 

$app->get('/shop_staffs', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - shop_staffs";
}); 

$app->get('/staffroles', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - staffroles";
}); 

$app->get('/states', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - states";
}); 

$app->get('/users', function() use($app) {
    $app->response->setStatus(200);
    echo "Welcome to Slim 3.0 based API - Users";
}); 

//include_once("address_users.php"); 
//include_once("beacons_company.php");
//include_once("beacons_master.php"); 
include_once("city.php"); 
include_once("company.php"); 
include_once("companyshop.php");
include_once("country.php");
include_once("productbrand.php");
include_once("productcategory.php");
include_once("colors.php");
include_once("manufacturer.php");
include_once("productpaymentmethod.php");
include_once("productsize.php");
include_once("productstatus.php");
include_once("products.php");
include_once("shop_staffs.php");
//include_once("staffroles.php");
include_once("states.php");
//include_once("users.php");

function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
    //echo $response;
}


$app->run();

function mt_rand_str ($l, $c = 'abcdefghijklmnopqrstuvwxyz1234567890') {
    for ($s = '', $cl = strlen($c)-1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);
    return $s;
}
?>
