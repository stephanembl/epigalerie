<?php

/**
 * Description of Login
 *
 * @author stef
 */
 
class Login {

	private $PDO;
    private $log_tek = "mombul_s";
    private $pass_tek = "xxxxxx";
	
    public function __construct($PDO){
		$this->PDO=$PDO;
    }

    public function check_login($login, $ppp){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://intra.epitech.eu/?format=json");
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HEADER, 1);

		$data = array(
			'login' => $login,
			'password' => $ppp
		);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$content = curl_exec( $ch );
		$err     = curl_errno( $ch );
		$errmsg  = curl_error( $ch );
		$header  = curl_getinfo( $ch );
		
		curl_close($ch);
		$header['errno']   = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content;
		return $header;
	}
	
	public function checkRights($login,$rights){
		$res = $this->PDO->prepare("SELECT adm_rights FROM adm_rights WHERE adm_login=? AND adm_rights=?");
		$success = $res->execute(array($login,$rights));
		if ($res->rowCount() == 1)
		{
			return (true);
		} else {
			return (false);
		}
	}
	
	public function changeRights($login,$rights){
		$res = $this->PDO->prepare("INSERT INTO adm_rights (adm_login, adm_rights) VALUES (?, ?) ON DUPLICATE KEY UPDATE adm_rights=?");
		$success = $res->execute(array($login,$rights,$rights));
		return ($res->rowCount());
	}
	
	public function getCity($login){
		$url = "http://ws.paysdu42.fr/JSON/?action=get_city&auth_login=".$this->log_tek."&auth_password=".$this->pass_tek."&login=".$login;
		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => false,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_ENCODING       => "",
			CURLOPT_USERAGENT      => "spider",
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0
		);

		$ch      = curl_init( $url );
		curl_setopt_array( $ch, $options );
		$content = curl_exec( $ch );
		$err     = curl_errno( $ch );
		$errmsg  = curl_error( $ch );
		$header  = curl_getinfo( $ch );
		curl_close( $ch );

		$header['errno']   = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content;
		$json = json_decode($header['content'], true);
		if ($json['error'] == 'none' && $json['result']['city'] != NULL)
		{
			return ($json['result']['city']);
		} else {
			return (NULL);
		}
	}
	
	public function getPromo($login){
		$url = "http://ws.paysdu42.fr/JSON/?action=get_promo&auth_login=".$this->log_tek."&auth_password=".$this->pass_tek."&login=".$login;
		$options = array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => false,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_USERAGENT      => "spider",
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0
		);

		$ch      = curl_init( $url );
		curl_setopt_array( $ch, $options );
		$content = curl_exec( $ch );
		$err     = curl_errno( $ch );
		$errmsg  = curl_error( $ch );
		$header  = curl_getinfo( $ch );
		curl_close( $ch );

		$header['errno']   = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content;
		$json = json_decode($header['content'], true);
		if ($json['error'] == 'none' && $json['result']['promo'] != NULL)
		{
			return ($json['result']['promo']);
		} else {
			return (NULL);
		}
	}
}
?>
