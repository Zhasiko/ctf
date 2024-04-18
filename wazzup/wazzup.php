<?
$wazzup = new WAZZUP;
class WAZZUP
{
	public $Watsapp;

	function __construct()
	{
		$this->Watsapp = new Watsapp;
	}

	function __destruct()
	{
		unset($this->Watsapp);
	}
}

class Watsapp
{
	public $apiKey = '';
	public $channelId = '';
	
	function __construct()
	{
		$this->getChannelId();
	}
	
	// получить channelId ==========================
	public function getChannelId()
	{
		$url = 'https://api.wazzup24.com/v2/channels';
		$header = array(
			'Authorization: Basic '. $this->apiKey,
			'Content-Type:application/json'
		);
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		
		$channelId = '';
		$response = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($http_code == 200)
		{
			$result = json_decode($response, true);

			foreach($result as $k=>$v)
			{
				if ($v["transport"] == 'whatsapp')
				{
					$channelId = $v["channelId"];
					break;
				}
			}		
		}

		curl_close($curl);

		$this->channelId = $channelId;

		return $channelId;
	}

	// отправить сообщение ==========================
	public function sendMessage($phone, $message, $contentUri)
	{
		$url = 'https://api.wazzup24.com/v2/send_message';
		$header = array(
			'Authorization: Basic '. $this->apiKey,
			'Content-Type:application/json'
		);
  		
		$messageId = '';
		$chatType = 'whatsapp';

		if ($message != '')
		{
			$data = array(
			   'channelId' => $this->channelId,
			   'chatId' => $phone,
			   'chatType' => $chatType,
			   'text' => $message
			);
		}
		else if ($contentUri != '')
		{
			$data = array(
			   'channelId' => $this->channelId,
			   'chatId' => $phone,
			   'chatType' => $chatType,
			   'text' => $contentUri
			);
		}
		
		$data_json = json_encode($data);
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($http_code == 201)
		{
			$result = json_decode($response, true);
			$messageId = $result["messageId"];
		}
		
		curl_close ($curl);
		
		return $messageId;
	}
	
	// открыть веб хуки ==========================
	public function openWebhooks()
	{
		$info = Array();
		
		$url = 'https://api.wazzup24.com/v2/webhooks';
		$header = array(
			'Authorization: Basic '. $this->apiKey,
			'Content-Type:application/json'
		);
		
		$data = array(
		   'url' => 'https://'.$_SERVER['HTTP_HOST'].'/wazzup/webhooks.php'		   
		);
		
		$data_json = json_encode($data);
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
			
		$response = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($http_code == 200)
		{
			$result = json_decode($response, true);
			
			$webhooksUrl = $result["webhooksUrl"];
			$webhooksUrlChanged = $result["webhooksUrlChanged"];
			
			$info["webhooksUrl"] = $webhooksUrl;
			$info["webhooksUrlChanged"] = $webhooksUrlChanged;
		}		

		//$info["http_code"] = $http_code;
		//$info["curl_error"] = curl_error($curl);
		//$info["result"] = $result;
				
		curl_close($curl);		
		
		return $info;
	}
}
?>
