<?php
class GDRegister {
	public function __construct ($h, $u, $p, $e){
		include __DIR__."/../config/config.php";
		$h = str_replace("boomlings", "www.boomlings", $h);
		$url = $h."/accounts/registerGJAccount.php";
		
		$username = $u;
		$password = $p;
		$email = $e;
		
		$post["userName"] = $username;
		$post["password"] = $password;
		$post["email"] = $email;
		$post["secret"] = "Wmfv3899gc9";
		$post["gameVersion"] = '22';
		$post["binaryVersion"] = '35';
		
		$ch = curl_init ($url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt ($ch, CURLOPT_HTTPHEADER, ["accept: */*", "content-type: application/x-www-form-urlencoded"]);
		$res = curl_exec ($ch);
		}
	}