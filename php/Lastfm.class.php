<?php
class Lastfm
{
	private $BaseUrl = "http://ws.audioscrobbler.com/2.0/?method=%s&format=json&api_key=%s";
	private $ApiKey;

	public function __construct($apiKey) 
	{
		$this->ApiKey = $apiKey;
	}

	public function GetNowPlaying($user)
	{
		try {
			$result = $this->DoApiCall("user.getRecentTracks", [
					"user" => 		$user,
					"limit" => 		"1",
					"nowplaying" => "true",
				]);

			if ($result && array_key_exists("recenttracks", $result) && count($result["recenttracks"]["track"]) > 0) {
				$track = $result["recenttracks"]["track"][0];
				if ($track["@attr"]["nowplaying"] === "true") {
					return [
						"artist" =>	$track["artist"]["#text"],
						"name" =>	$track["name"],
						"album" =>	$track["album"]["#text"],
						"link" =>	$track["url"],
					];
				}				
			}
		} 
		catch (Exception $ex) {
			Debug::WriteLine($ex);
		}
		return null;
	}

	protected function DoApiCall($method, $args = [])
	{
		try {
			$url = sprintf($this->BaseUrl, $method, $this->ApiKey);
			foreach ($args as $key => $value) {
				$url .= "&$key=" . urlencode($value);
			}

			Debug::WriteLine($url);
			$response = file_get_contents($url);
			$data = json_decode($response, true); // Last.fm's JSON in combination with this is filled with bullshit
			Debug::Dump($data);

			if ($data && array_key_exists("error", $data)) {
				throw new Exception($data["message"]);
			}

			return $data;
		} 
		catch (Exception $ex) {
			Debug::WriteLine($ex);
		}
		return false;
	}
}