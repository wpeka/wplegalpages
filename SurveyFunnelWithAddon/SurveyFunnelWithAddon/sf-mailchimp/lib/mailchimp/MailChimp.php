<?php
class SFMailChimpIN
{
	private $api_key;
	private $api_endpoint = 'https://<dc>.api.mailchimp.com/3.0/';
	private $verify_ssl   = false;

	function __construct($api_key)
	{
		$this->api_key = $api_key;
		list(, $datacentre) = explode('-', $this->api_key);
		$this->api_endpoint = str_replace('<dc>', $datacentre, $this->api_endpoint);
	}

	public function call( $args=array() )
	{
		return $this->_raw_request( $args );
	}

	private function _raw_request( $args=array() )
	{      
		$auth = base64_encode( 'user:'.$this->api_key );
		if( !$this->user_exits( $args ) ){
		$url = $this->api_endpoint.'lists/'.$args['list_id'].'/members/';
		
		$data = array(
			'email_address'=> $args['email'],
			'status'=>'subscribed',
			'merge_fields'=>array( 'FNAME'=>$args['uname'] )			
		);
		
		
		$result = wp_remote_post( $url, array(
				'method' => 'POST',
				'headers' => array( 'Content-Type'=> 'application/json','Authorization'=>'Basic '.$auth ),
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'user-agent' => 'PHP-MCAPI/3.0',
				'sslverify' => $this->verify_ssl,
				'body' => json_encode( $data )
				
			) );
		}
		else{
			$data = array(
					'email_address'=> $args['email'],
					'merge_fields'=>array( 'FNAME'=>$args['uname'] )
			);
			$url =  $this->api_endpoint.'lists/'.$args['list_id'].'/members/'.md5( $args['email'] );
			$result = wp_remote_post( $url, array(
					'method' => 'PATCH',
					'headers' => array( 'Content-Type'=> 'application/json','Authorization'=>'Basic '.$auth ),
					'timeout' => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'user-agent' => 'PHP-MCAPI/3.0',
					'sslverify' => $this->verify_ssl,
					'body' => json_encode( $data )
			
			) );
		}
	}
	
	private function user_exits( $args ){
		
		$url = $this->api_endpoint."lists/".$args['list_id']."/members/".md5( $args['email'] )."?apikey=".$this->api_key;
		$response = wp_remote_get( $url, array( 'ssl_verify'=>false ) );
		if( $response['response']['code'] == '404' )
		{
			return false;
		} else{
			return true;
		}
		
	}

}