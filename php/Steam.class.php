<?php
class Steam
{
	private $BaseUrl = "http://api.steampowered.com/%s/%s/v%s/?key=%s&format=json";
	private $ApiKey = null;
	private $PlayerCache = [];

	public function __construct($apiKey) 
	{
		$this->ApiKey = $apiKey;
	}

	public static function PersonaStateToString($personaState)
	{
		switch ($personaState) {
			case 1: return "Online";
			case 2: return "Busy";
			case 3: return "Away";
			case 4: return "Snooze";
			case 5: return "Looking to Trade";
			case 6: return "Looking to Play";
			default: return "Offline";
		}
	}

	public function GetName($steamId64) {
		Debug::WriteLine("GetName");
		$summary = $this->GetPlayerSummary($steamId64);
		if ($summary) {
			return $summary->personaname;
		}
		return null;
	}

	public function GetStatus($steamId64) {
		Debug::WriteLine("GetStatus");
		$summary = $this->GetPlayerSummary($steamId64);
		if ($summary) {
			if (isset($summary->gameid)) {
				if (isset($summary->gameextrainfo))
					return $summary->gameextrainfo;
				return "In-Game";
			}
			return self::PersonaStateToString($summary->personastate);
		}
		return null;
	}

	public function GetStatusSimple($steamId64)
	{
		Debug::WriteLine("GetStatusSimple");
		$summary = $this->GetPlayerSummary($steamId64);
		if ($summary) {
			if (isset($summary->gameid)) {
				return "ingame";
			}
			else if ($summary->personastate > 0) {
				return "online";
			}
		}
		return "offline";
	}

	public function GetPlayerSummary($steamId64)
	{
		try {
			if (isset($this->PlayerCache[$steamId64])) {
				Debug::WriteLine("Returning cached summary for $steamId64");
				return $this->PlayerCache[$steamId64];
			}
			else {
				$result = $this->DoApiCall("ISteamUser", "GetPlayerSummaries", "0002", [
						"steamids" => $steamId64,
					]);
				if ($result && isset($result->response) && isset($result->response->players[0])) {
					$summary = $result->response->players[0];
					$this->PlayerCache[$steamId64] = $summary;
					return $summary;
				}
			}
		} 
		catch (Exception $ex) {
			Debug::WriteLine($ex);
		}
		return null;
	}

	protected function DoApiCall($interface, $method, $version, $args = [])
	{
		try {
			$url = sprintf($this->BaseUrl, $interface, $method, $version, $this->ApiKey);
			foreach ($args as $key => $value) {
				$url .= "&$key=$value";
			}

			Debug::WriteLine($url);
			$response = file_get_contents($url);
			$data = json_decode($response);
			Debug::Dump($data);

			return $data;
		} 
		catch (Exception $ex) {
			Debug::WriteLine($ex);
		}
		return false;
	}
}