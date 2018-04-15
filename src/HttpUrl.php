<?php 
namespace HttpUrl;

class HttpUrl{
	private $transfer_mode;
	private $mode=array('curl'=>'curl','fopen'=>'fopen');
	private $encoding=array('json'=>'json','form'=>'form');
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

	public function post($url,$data,$encoding=null,$transfer_mode=null,$params=null){
		if(!$transfer_mode) $transfer_mode=$this->transfer_mode;
		switch($transfer_mode){
			case "{$this->mode['curl']}":
				return $this->post_curl($url,$params,$data,$encoding);
				break;
			default:
				return $this->post_fopen($url,$params,$data,$encoding);
				break;
		}
	}

	public function put($url,$data,$encoding=null,$transfer_mode=null,$params=null){
		if(!$transfer_mode) $transfer_mode=$this->transfer_mode;
		switch($transfer_mode){
			case "{$this->mode['curl']}":
				return $this->put_curl($url,$params,$data,$encoding);
				break;
			default:
				return $this->put_fopen($url,$params,$data,$encoding);
				break;
		}
	}

	public function delete($url,$data,$encoding=null,$transfer_mode=null,$params=null){
		if(!$transfer_mode) $transfer_mode=$this->transfer_mode;
		switch($transfer_mode){
			case "{$this->mode['curl']}":
				return $this->delete_curl($url,$params,$data,$encoding);
				break;
			default:
				return $this->delete_fopen($url,$params,$data,$encoding);
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

	private function post_curl($url,$params=null,$data,$encoding=null){
		$url=$this->build_url($url,$params);
		$options = array(CURLOPT_URL => $url,
			CURLOPT_POST => true,
			//CURLOPT_POSTFIELDS => $data,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => $this->includeheaders
			);
		if(!$encoding) $encoding=$this->post_encode;
		switch($encoding){
			case "{$this->encoding['json']}":
				$data_string = json_encode($data);
				$options[CURLOPT_HTTPHEADER] = array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($data_string));
				$options[CURLOPT_POSTFIELDS] = $data_string;
				break;
			default:
				$options[CURLOPT_POSTFIELDS] = http_build_query($data);
				break;
		}
		//var_dump($options);
		return $this->perform_curl($url,$options);
	}

	private function put_curl($url,$params=null,$data,$encoding=null){
		$url=$this->build_url($url,$params);
		$options = array(CURLOPT_URL => $url,
			CURLOPT_PUT => true,
			//CURLOPT_POSTFIELDS => $data,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => $this->includeheaders
			);
		if(!$encoding) $encoding=$this->post_encode;
		switch($encoding){
			case "{$this->encoding['json']}":
				$data_string = json_encode($data);
				$options[CURLOPT_HTTPHEADER] = array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($data_string));
				$options[CURLOPT_POSTFIELDS] = $data_string;
				break;
			default:
				$options[CURLOPT_POSTFIELDS] = http_build_query($data);
				break;
		}
		//var_dump($options);
		return $this->perform_curl($url,$options);
	}

	private function delete_curl($url,$params=null,$data,$encoding=null){
		$url=$this->build_url($url,$params);
		$options = array(CURLOPT_URL => $url,
			CURLOPT_DELETE => true,
			//CURLOPT_POSTFIELDS => $data,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => $this->includeheaders
			);
		if(!$encoding) $encoding=$this->post_encode;
		switch($encoding){
			case "{$this->encoding['json']}":
				$data_string = json_encode($data);
				$options[CURLOPT_HTTPHEADER] = array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($data_string));
				$options[CURLOPT_POSTFIELDS] = $data_string;
				break;
			default:
				$options[CURLOPT_POSTFIELDS] = http_build_query($data);
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

	private function post_fopen($url,$params=null,$data,$encoding=null){
		$opts = array('http' =>
		array(
			'method'  => 'POST'
			)
		);
		if(!$encoding) $encoding=$this->post_encode;
		switch($encoding){
			case "{$this->encoding['json']}":
				$data_string = json_encode($data);
				$opts['http']['header'] = array('Content-Type: application/json','Content-Length: ' . strlen($data_string));
					$opts['http']['content'] = $data_string;
				break;
			default:
					$opts['http']['header'] = ['Content-type: application/x-www-form-urlencoded'];
					$opts['http']['content'] = http_build_query($data);
				break;
		}
		$context  = stream_context_create($opts);
		return file_get_contents($url, false, $context);
	}

	private function put_fopen($url,$params=null,$data,$encoding=null){
		$opts = array('http' =>
		array(
			'method'  => 'PUT'
			)
		);
		if(!$encoding) $encoding=$this->post_encode;
		switch($encoding){
			case "{$this->encoding['json']}":
				$data_string = json_encode($data);
				$opts['http']['header'] = array('Content-Type: application/json','Content-Length: ' . strlen($data_string));
					$opts['http']['content'] = $data_string;
				break;
			default:
					$opts['http']['header'] = ['Content-type: application/x-www-form-urlencoded'];
					$opts['http']['content'] = http_build_query($data);
				break;
		}
		$context  = stream_context_create($opts);
		return file_get_contents($url, false, $context);
	}

	private function delete_fopen($url,$params=null,$data,$encoding=null){
		$opts = array('http' =>
		array(
			'method'  => 'DELETE'
			)
		);
		if(!$encoding) $encoding=$this->post_encode;
		switch($encoding){
			case "{$this->encoding['json']}":
				$data_string = json_encode($data);
				$opts['http']['header'] = array('Content-Type: application/json','Content-Length: ' . strlen($data_string));
					$opts['http']['content'] = $data_string;
				break;
			default:
					$opts['http']['header'] = ['Content-type: application/x-www-form-urlencoded'];
					$opts['http']['content'] = http_build_query($data);
				break;
		}
		$context  = stream_context_create($opts);
		return file_get_contents($url, false, $context);
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
