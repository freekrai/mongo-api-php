<?php
class MongoAPI{
	private $db;
	private $col;
	private $apiKey;

	public function __construct($db,$col,$apiKey){
		$this->db = $db;
		$this->col = $col;
		$this->apiKey = $apiKey;
	}

	public function get($key=''){
		if( !empty($key) ){
			$url = 'https://api.mongolab.com/api/1/databases/'.$this->db.'/collections/'.$this->col.'/'.$key.'?apiKey='.$this->apiKey;
		}else{
			$url = 'https://api.mongolab.com/api/1/databases/'.$this->db.'/collections/'.$this->col.'/?apiKey='.$this->apiKey;
		}
		$ret = $this->get_query($url);
		return json_decode( $ret,true );
	}

	public function query( $query ){
		$q = json_encode( $query );
		$q = urlencode( $q );
		$url = 'https://api.mongolab.com/api/1/databases/'.$this->db.'/collections/'.$this->col.'/?apiKey='.$this->apiKey.'&q='.$q;
		$ret = $this->get_query($url);
		return json_decode( $ret,true );
	}

	public function insert($row){
		$url = 'https://api.mongolab.com/api/1/databases/'.$this->db.'/collections/'.$this->col.'/?apiKey='.$this->apiKey;
		$row = json_encode( $row );
		$ret = $this->post_query($url,$row);
		$ret = json_decode( $ret,true );
		$id = $ret['_id']['$oid'];
		return $id;
	}

	public function update($row,$key){
		$url = 'https://api.mongolab.com/api/1/databases/'.$this->db.'/collections/'.$this->col.'/'.$key.'?apiKey='.$this->apiKey;
		$row = json_encode( $row );
		$ret = $this->put_query($url,$row);
		$ret = json_decode( $ret,true );
		return $ret;
	}

	public function delete($key){
		$url = 'https://api.mongolab.com/api/1/databases/'.$this->db.'/collections/'.$this->col.'/'.$key.'?apiKey='.$this->apiKey;
		$ret = $this->del_query($url);
		return json_decode( $ret,true );
	}

	private function get_query($url){
		$ch = curl_init($url);
		curl_setopt($ch,CURLOPT_HEADER,false);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	private function del_query($url){
		$ch = curl_init($url);
		curl_setopt($ch,CURLOPT_HEADER,false);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	private function put_query($url,$args){
		$ch = curl_init($url);
		$timeout=5;
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");    
		curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
		$data = curl_exec($ch);
		curl_close($ch);        
		return $data;
	}

	private function post_query($url,$args){
		$ch = curl_init($url);
		$timeout=5;
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
		$data = curl_exec($ch);
		curl_close($ch);        
		return $data;
	}
}