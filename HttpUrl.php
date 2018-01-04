<?php 
require_once 'file_get_contents.php';

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
			case 'xfopen':
				$this->transfer_mode='xfopen';
				break;
			defualt:
				$this->transfer_mode='fopen';
				break;
		}
	}
	private function retreive_data_fopen($url){
		$str=file_get_contents($url);
		return $str;
	}
	private function retreive_data_xfopen($url){
		$str=php_compat_file_get_contents($url);
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
	public function get_data($url){
		switch($this->transfer_mode){
			case 'curl':
				return $this->retreive_data_curl($url);
				break;
			case 'xfopen':
				return $this->retreive_data_xfopen($url);
				break;
			default:
				return $this->retreive_data_fopen($url);
				break;
		}
	}
	public function test_connections(){
		//$url="http://weather.noaa.gov/pub/data/observations/metar/stations/LIRF.TXT";
		$url="http://tgftp.nws.noaa.gov/data/observations/metar/stations/LIRF.TXT";
		echo "grab test:<br>";
		echo "file_get_contents:";
		echo $this->retreive_data_fopen($url);
		echo ":<br>";
		echo "curl:";
		echo $this->retreive_data_curl($url);
		echo ":<br>";
	}
}
?>