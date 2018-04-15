<?php
$resp=array();
$resp['method']=$_SERVER['REQUEST_METHOD'];
switch($_SERVER['REQUEST_METHOD']){
	case 'GET':
		if(isset($_GET['data1'])) $resp['data1']=$_GET['data1'];
		break;
	case 'POST':
		//for content-type json: uncomment...
		//$_POST=json_decode(file_get_contents("php://input"),true);
		if(isset($_POST['data1'])) $resp['data1']=$_POST['data1'];
		break;
	case 'PUT':
		//for content-type json: switch...
		//$post_vars=json_decode(file_get_contents("php://input"),true);
		parse_str(file_get_contents("php://input"),$post_vars);
		if(isset($post_vars['data1'])) $resp['data1']=$post_vars['data1'];
		break;
	case 'DELETE':
		//for content-type json: switch...
		//$post_vars=json_decode(file_get_contents("php://input"),true);
		parse_str(file_get_contents("php://input"),$post_vars);
		if(isset($post_vars['data1'])) $resp['data1']=$post_vars['data1'];
		break;
	default:
		echo "method: unknown";
		break;
}
echo json_encode($resp);
?>