<?php 
namespace HttpUrl;

class HttpUrl{
	private $transfer_mode;
	private $mode=array('curl'=>'curl','fopen'=>'fopen');
	private $encoding=array('json'=>'','form'=>'');
	private $post_encode;
	private $includeheaders=false;
	function __construct($mode=null){
		$this->set_transfer_mode($mode);
		$this->set_post_encode();
	}
	public function set_transfer_mode($mode=null){
		if($mode==null) return;
		switch($mode){
			case 'curl':
				$this->transfer_mode=$this->mode['curl'];
				break;
			default:
				$this->transfer_mode=$this->mode['fopen'];
				break;
		}
	}

	public function set_post_encode($enc=null){
		switch($enc){
			case 'json':
				$this->post_encode=$this->encoding['json'];
				break;
			default:
				$this->post_encode=$this->encoding['form'];
				break;
		}
	}

	public function set_show_headers(bool $bool){
		$this->includeheaders=$bool;
	}
	//get, post, put, delete
	//get_curl,post_curl,put_curl,delete_curl
	//get_fopen,post_fopen,put_fopen,delete_fopen
	//curl_req
	//fopen_req

	//get(url,params,transfer_mode)
	//post(url,params,data,encoding,transfer_mode)

	private function build_url($url,$params,$questionmark=true){
		//$params == array(...)
		if($questionmark) if($params && $params[0]!='?') $params = "?". http_build_query($params);
		return $url.$params;
	}
	public function get($url,$params=null,$transfer_mode=null){
		if(!$transfer_mode) $transfer_mode=$this->transfer_mode;
		switch($transfer_mode){
			case "{$this->mode['curl']}":
				return $this->get_curl($url,$params);
				break;
			default:
				return $this->get_fopen($url,$params);
				break;
		}
	}

	public function post($url,$params=null,$data,$encoding=null,$transfer_mode=null){
		if(!$transfer_mode) $transfer_mode=$this->transfer_mode;
		switch($transfer_mode){
			case "{$this->mode['curl']}":
				return $this->post_curl($url,$params,$data,$encoding);
				break;
			default:
				//return $this->get_fopen($url,$params);
				break;
		}
	}

	private function get_curl($url,$params){
		$url=$this->build_url($url,$params);
		$options = array(CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
								CURLOPT_HEADER => $this->includeheaders
                );
		return $this->perform_curl($url,$options);
	}

	private function post_curl($url,$params,$data,$encoding=null){
		$url=$this->build_url($url,$params);
		$options = array(CURLOPT_URL => $url,
								CURLOPT_POST => true,
								//CURLOPT_POSTFIELDS => $data,
                CURLOPT_RETURNTRANSFER => true,
								CURLOPT_HEADER => $this->includeheaders
                );
		if(!$encoding) $encoding=$this->post_encode;
		switch($encoding){
			case "$this->encoding['json']":
				//echo "json enc";
				break;
			default:
				$options[CURLOPT_POSTFIELDS]= http_build_query($data);
				break;
		}
		//var_dump($options);
		return $this->perform_curl($url,$options);
	}

	private function perform_curl($url,$options){
		if(!function_exists('curl_init')) return "curl not exists";
		$curl = curl_init();
		curl_setopt_array($curl, $options);
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}

	private function get_fopen($url,$params){
		return file_get_contents($this->build_url($url,$params));
	}
	
	public function post_data($url,$data=null){
		if(!function_exists('curl_init')) return "curl not exists";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
	
	public function test_connections($url=null,$endline=null){
		//$url="http://weather.noaa.gov/pub/data/observations/metar/stations/LIRF.TXT";
		$metar="http://tgftp.nws.noaa.gov/data/observations/metar/stations/LIRF.TXT";
		if(!$url) $url=$metar;
		if(!$endline) $endline="<br>\n";
		echo "grab test:$endline";
		echo "file_get_contents:";
		echo $this->get($url,null,$this->mode['fopen']);
		echo ":{$endline}";
		echo "curl:";
		echo $this->get($url,null,$this->mode['curl']);
		echo ":{$endline}";
	}
}
?>