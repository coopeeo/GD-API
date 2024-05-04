<?php
class GDConfig {
	public $host;
	protected $username;
	protected $password;
	protected $gjp;
	public $accountID;
	public $userID;
	
	public function __construct ($a, $b = null, $c = null){
		//Set Host GD
		$this->host = $a;
		$this->host = str_replace("boomlings", "www.boomlings", $this->host);
		$this->username = $b;
		$this->password = $c;
		
		if (!empty ($c)){
			$ch = curl_init ();
			$udid = "S" . mt_rand(111111111,999999999) . mt_rand(111111111,999999999) . mt_rand(111111111,999999999) . mt_rand(111111111,999999999) . mt_rand(1,9); 
			$sid = mt_rand(111111111,999999999) . mt_rand(11111111,99999999);
			curl_setopt ($ch, CURLOPT_URL, $this->host."/accounts/loginGJAccount.php");
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query(["secret" => "Wmfv3899gc9", "userName" => $this->username, "password" => $this->password, "udid" => $udid, "sID" => $sid, "gameVersion" => '22', "binaryVersion" => '35']));
			if (curl_exec($ch) == "-1"){
				include __DIR__."/Exception/GDErrors.php";
				$err = new GDErrors($this->host);
				GDErrors::GDLogin();
				}
				$info = explode (",", curl_exec ($ch));
				$this->accountID = $info[0];
				$this->userID = $info[1];
				}
	}
	
	public function getUserID (){
		return $this->userID;
		}
	public function getGJP(){
		$convert = xorEncrypt($this->password, 37526);
		return $this->gjp;
		}
	public function getAccountID (){
		return $this->accountID;
		}
	public function getHost (){
		return $this->host;
		}
	public function getAccInfo ($t, $i = "icon"){
		include __DIR__."/GDProfile.php";
		$app = new GDProfile ($this->host, $this->accountID);
		$t = preg_replace ("/[^a-zA-Z0-9\s]/", " ", $t);
		switch ($t){
			case "demons":
			return $app->getDemon();
			break;
			case "stars":
			return $app->getStars();
			break;
			case "CP":
			return $app->getCP();
			break;
			case "goldCoins":
			return $app->getGoldCoins();
			break;
			case "coins":
			return $app->getCoins();
			break;
			case "diamonds":
			return $app->getDiamonds();
			break;
			case "youtube":
			return $app->getYouTube();
			break;
			case "twitch":
			return $app->getTwitch();
			break;
			case "twitter":
			return $app->getTwitter();
			break;
			case "icon":
			return $app->getIcon ($i);
			break;
			default:
			echo "<b>Error:</b>Invalid in type $t, Please read at READ.md to get more type";
			break;
			}
		}
	public function downloadSaveData ($p){
		include __DIR__."/../config/config.php";
		$url = $this->getAccountURL();
		$url = $url."/database/accounts/syncGJAccountNew.php";
		$postfields["userName"] = $this->username; 
		$postfields["accountID"] = $this->accountID;
		$postfields["password"] = $this->password;
		$postfields["secret"] = "Wmfv3899gc9";
		$postfields["gdw"] = 0;
		$postfields["gameVersion"] = '22';
		$postfields["binaryVersion"] = '35';

		
		$ch = curl_init ($url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
		curl_setopt ($ch, CURLOPT_HTTPHEADER, ["accept: */*", "content-type: application/x-www-form-urlencoded"]);
		$response = curl_exec ($ch);
		file_put_contents ($p, explode (";", $response)[0]);
		return $response;
		}
	public function uploadSaveData ($kizunaAI){
		include __DIR__."/../config/config.php";
		$url = $this->getAccountURL();
		$url = $url."/database/accounts/backupGJAccountNew.php";
		$postfields["gameVersion"] = '22';
		$postfields["binaryVersion"] = '35';
		$postfields["userName"] = $this->username; 
		$postfields["accountID"] = $this->accountID;
		$postfields["password"] = $this->password;
		$postfields["secret"] = "Wmfv3899gc9";
		$postfields["saveData"] = $KizunaAI;
		$postfields["gdw"] = 0;
		
		$ch = curl_init ($url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
		curl_setopt ($ch, CURLOPT_HTTPHEADER, ["accept: */*", "content-type: application/x-www-form-urlencoded"]);
		$response = curl_exec ($ch);
		if ($response == "-1" || empty ($response)){
			return "Upload Save Data Failed! / ". $response;
			} else {
			return "Upload Save Data Success! / ". $url." / ". $response;
			}
		}
	private function getAccountURL(){
		$postURL["accountID"] = $this->accountID;
		$postURL["type"] = "2";
		$postURL["secret"] = "Wmfd2893gb7";
		$postURL["gameVersion"] = '22';
		$postURL["binaryVersion"] = '35';
		
			
		$ch = curl_init ($this->host."/getAccountURL.php");
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($postURL));
		curl_setopt ($ch, CURLOPT_HTTPHEADER, ["accept: */*", "content-type: application/x-www-form-urlencoded"]);
		$newHost = curl_exec ($ch);
		curl_close ($ch);
		return $newHost;
		}
}