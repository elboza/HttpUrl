<?php 

class HttpUrl{
	private $transfer_mode;
	function __construct($mode=null){
		$this->set_transfer_mode($mode);
	}
	public function set_transfer_mode($mode=null){
		if($mode==null) return;
		switch($mode){
			case 'curl':
				$this->transfer_mode='curl';
				break;
			defualt:
				$this->transfer_mode='fopen';
				break;
		}
	}
	//get, post, put, delete
	//get_curl,post_curl,put_curl,delete_curl
	//get_fopen,post_fopen,put_fopen,delete_fopen
	//curl_req
	//fopen_req

	//get(url,params,transfer_mode)
	//post(url,params,data,encoding,transfer_mode)


	private function retreive_data_fopen($url){
		$str=file_get_contents($url);
		return $str;
	}
	private function retreive_data_curl($url){
	if(!function_exists('curl_init')) return "curl not exists";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
	public function post_data($url,$data=null){
		if(!function_exists('curl_init')) return "curl not exists";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
	public function get_data($url){
		switch($this->transfer_mode){
			case 'curl':
				return $this->retreive_data_curl($url);
				break;
			default:
				return $this->retreive_data_fopen($url);
				break;
		}
	}
	public function test_connections($url=null,$endline=null){
		//$url="http://weather.noaa.gov/pub/data/observations/metar/stations/LIRF.TXT";
		$metar="http://tgftp.nws.noaa.gov/data/observations/metar/stations/LIRF.TXT";
		if(!$url) $url=$metar;
		if(!$endline==null) $endline="<br>";
		echo "grab test:{$endline}";
		echo "file_get_contents:";
		echo $this->retreive_data_fopen($url);
		echo ":{$endline}";
		echo "curl:";
		echo $this->retreive_data_curl($url);
		echo ":{$endline}";
	}
}
?>