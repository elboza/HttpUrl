# HttpUrl
### easy http url in PHP

```
set_transfer_mode ~~> 'curl' | 'fopen'
set_post_encode   ~~> 'json' | 'form'
set_show_headers  ~~>  true  |  false 
```

```
get    ($url,$params=null,$transfer_mode=null)
post   ($url,$data,$encoding=null,$transfer_mode=null,$params=null)
put    ($url,$data,$encoding=null,$transfer_mode=null,$params=null)
delete ($url,$data,$encoding=null,$transfer_mode=null,$params=null)
```
`$data` has to be an array.

## example
`test.php` file:
```
<?php
require '../src/HttpUrl.php';
use HttpUrl\HttpUrl;

$x=new HttpUrl();

//show test connections
$x->test_connections();

//select transfer mode (curl or fopen)
//$x->set_transfer_mode('curl');

//set if show headersin response
//$x->set_show_headers(true);

$url='http://localhost/~drugo/httpurl/test/test_server.php';

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
```
