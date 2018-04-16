<?php
require_once __DIR__ . '/../vendor/autoload.php';
use HttpUrl\HttpUrl;

$x=new HttpUrl();

//show test connections
$x->test_connections();

//select transfer mode (curl or fopen)
$x->set_transfer_mode('curl');

//set if show headersin response
//$x->set_show_headers(true);

//run http server in the project root directory:
//php -S localhost:3000 -r .
$url='localhost:3000/test/test_server.php';

echo "<br><br>";
echo $x->get($url,array('data1'=>42));

echo "<br><br>";
echo $x->post($url,array('data1'=>'44'));

echo "<br><br>";
echo $x->put($url,array('data1'=>'47'));

echo "<br><br>";
echo $x->delete($url,array('data1'=>'48'));

//add if json encoded request
//echo $x->delete($url,array('data1'=>'48'),'json');
?>
