<?php
require '../src/HttpUrl.php';
use HttpUrl\HttpUrl;
$x=new HttpUrl();
$x->test_connections();
$x->set_transfer_mode('curl');
//$x->set_show_headers(true);
$url='http://localhost/~drugo/httpurl/test/test_server.php';
echo "<br><br>";
echo $x->get($url,array('data1'=>42));
echo "<br><br>";
echo $x->post($url,null,array('data1'=>'44'));
?>