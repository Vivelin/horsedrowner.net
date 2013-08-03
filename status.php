<?php
//Handles uncaught exceptions by returning them as JSON
function steam_json_exception_handler($exception) {
    echo <<<JSON
{ "error": "$exception" }

JSON;
}

set_exception_handler("steam_json_exception_handler");
require_once "config.php";

class Status {
    private $profile_url = "http://steamcommunity.com/id/horsedrowner";
    private $api_url = "http://ws.audioscrobbler.com/2.0/";
    private $xml = null;

    //Initializes a new instance by custom URL.
    public function __construct($custom_url) {
        $profile_url = "http://steamcommunity.com/id/$custom_url";
    }
    
    //Loads the profile so that other functions may be used.
    public function load() {
        $this->xml = self::getXml();
        
        if (!$this->xml) throw new Exception("The profile could not be loaded from ".$profile_url);
    }

    //Returns the XML object for this profile.
    private function getXml() {
        $old = error_reporting(E_ERROR);
        
        $ch = curl_init($this->profile_url."?xml=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        
        $response = curl_exec($ch);
        curl_close($ch);
        if (strlen($response) > 0) {
            $xml = new SimpleXMLElement($response);
            error_reporting($old);
            return $xml;
        }
            
        error_reporting($old);
        return null;
    }
    
    //Checks if the current state is valid. Raises an exception on error, or returns true.
    public function validate() {
        if ($this->xml == null) throw new Exception("The profile has not been loaded.");
        if ($this->xml->error) throw new Exception($this->xml->error);
        return true;
    }
    
    //Returns a string that represents the user's display name.
    public function getDisplayName() {
        self::validate();
        $name = (string)$this->xml->steamID;
        return $name;
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
    
    //Returns a string that represents the user's online status or in-game name.
    //In the event that the user is playing a joinable game, the string may also 
    //include a HTML "Join" link.
    public function getStateMessage() {
        self::validate();
        $state_message = str_ireplace(array('<br>', '<br />', '<br/>'), ' - ', 
                                      (string)$this->xml->stateMessage);
        $state_message = str_ireplace(array('In-Game - ', 'In non-Steam game - '), '', 
                                      $state_message);
        return $state_message;
    }
    
    //Returns a string that contains the user's status ("online", "in-game" or "offline").
    public function getOnlineState() {
        self::validate();
        $online_state = (string)$this->xml->onlineState;
        return $online_state;
    }
    
    //Returns a JSON-encoded string that represents the user's current status.
    public function getStatusJson() {
        self::validate();
        $link;
        $data = array(
            "url" => $this->profile_url,
            "onlineState" => self::getOnlineState(),
            "stateMessage" => self::getDisplayName()." - ".self::getStateMessage(),
            "nowPlaying" => self::getNowPlaying("horsedrowner", $link),
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

try {
    if (isset($_GET["id"])) $id = $_GET["id"]; else $id = "horsedrowner";

    if (file_exists("status.txt")) {
        $diff = time() - filemtime("status.txt");
        if ($diff < 15) {
            $json = Status::getCached();
        }
    }

    if (!isset($diff) || $diff >= 15 || $json === false) {
        $status = new Status($id);
        $status->load();
        $json = $status->getStatusJson();
    }
        
    echo $json;
}
catch (Exception $ex) {
    echo <<<JSON
{ "error": "{$ex->getMessage()}" }

JSON;
}
?>