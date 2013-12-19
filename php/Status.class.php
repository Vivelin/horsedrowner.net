<?php
class Status 
{
    private $api_url = "http://ws.audioscrobbler.com/2.0/";
    private $steamId;
    private $steam;

    //Initializes a new instance by custom URL.
    public function __construct($steamApiKey, $steamId64) {
        $this->steam = new Steam($steamApiKey);
        $this->steamId = $steamId64;
    }

    public function getNowPlaying($user = "horsedrowner", &$link) {
        global $lastfmAPI;

        try {
            $method = "user.getRecentTracks";
            $url = $this->api_url . "?method=" . urlencode($method) 
                 . "&format=json" 
                 . "&user=" . urlencode($user)
                 . "&limit=1"
                 . "&nowplaying=true"
                 . "&api_key=" . urlencode($lastfmAPI);
            $json = file_get_contents($url);
            $data = json_decode($json, true);

            if (array_key_exists("error", $data)) {
                throw new Exception($data["message"]);
            }

            $track = @$data["recenttracks"]["track"][0];
            $artist = $track["artist"]["#text"];
            $name = $track["name"];
            $album = $track["album"]["#text"];
            $link = $track["url"];

            $nowplaying = ($track["@attr"]["nowplaying"] === "true");
            if ($nowplaying) {
                return $artist . " - " . $name;
            }
        }
        catch (Exception $e) { 
            // echo $e->getMessage() . "\n"; 
        }
        return null;
    }
    
    //Returns a JSON-encoded string that represents the user's current status.
    public function getStatusJson() {
        $link;
        $data = array(
            "onlineState" => $this->steam->GetStatusSimple($this->steamId),
            "stateMessage" => $this->steam->GetName($this->steamId) . " - "
                . $this->steam->GetStatus($this->steamId),
            "nowPlaying" => $this->getNowPlaying("horsedrowner", $link),
            "nowPlayingLink" => $link,
        );
        
        $json = json_encode($data, JSON_HEX_TAG);

        if (@file_put_contents("status.txt", $json, LOCK_EX) === FALSE) {
            // Nothing we can do about it.
        }

        return $json;
    }

    public static function getCached() {
        try {
            if (!file_exists("status.txt"))
                return false;

            $json = file_get_contents("status.txt");

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