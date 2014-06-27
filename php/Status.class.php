<?php
class Status 
{
    private $api_url = "http://ws.audioscrobbler.com/2.0/";
    private $steamId;
    private $steam;
    private $lastfm;

    //Initializes a new instance by custom URL.
    public function __construct($steamApiKey, $steamId64, $lastfmApiKey) {
        $this->steam = new Steam($steamApiKey);
        $this->steamId = $steamId64;
        $this->lastfm = new Lastfm($lastfmApiKey);
    }

    public function getNowPlaying($user = "horsedrowner", &$link) {

        try {
            $track = $this->lastfm->GetNowPlaying($user);
            if ($track) {
                $link = $track["link"];
                return $track["artist"] . " - " . $track["name"];
            }
        }
        catch (Exception $e) { 
            // echo $e->getMessage() . "\n"; 
        }
        return null;
    }
    
    //Returns a JSON-encoded string that represents the user's current status.
    public function getSteamJson() {
        $summary = $this->steam->GetPlayerSummary($this->steamId);
        if ($summary)
        {
            $data = array(
                "onlineState" => $this->steam->GetStatusSimple($this->steamId),
                "stateMessage" => $this->steam->GetName($this->steamId) . " - "
                    . $this->steam->GetStatus($this->steamId),
            );
            
            $json = json_encode($data, JSON_HEX_TAG);
            if (@file_put_contents("status_steam.txt", $json, LOCK_EX) === FALSE) {
                // Nothing we can do about it.
            }

            return $json;
        }
    }

    public function getLastfmJson() 
    {
        $link;
        $data = [
            "text" => $this->getNowPlaying("horsedrowner", $link),
            "link" => $link,
        ];

        $json = json_encode($data, JSON_HEX_TAG);
        if (@file_put_contents("status_lastfm.txt", $json, LOCK_EX) === FALSE) {
            // Nothing we can do about it.
        }

        return $json;
    }

    public static function getCached($file = "status_steam.txt") {
        try {
            if (!file_exists($file))
                return false;

            $json = file_get_contents($file);

            if ($json === false)
                return false;

            /* Indicate we're returning a cached status */
            $data = json_decode($json, true);
            $data["cached"] = "true";

            return json_encode($data, JSON_HEX_TAG);
        } 
        catch (Exception $e) {
            // Boobs
        }
    }
}