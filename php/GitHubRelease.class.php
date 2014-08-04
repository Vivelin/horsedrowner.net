<?php
class GitHubRelease
{
    protected function __construct($data)
    {
        $this->version = $data[0]["tag_name"];
        $this->download = $data[0]["assets"][0]["browser_download_url"];
    }

    public $version;

    public static function RequestLatest($repo)
    {
        $request = HttpWebRequest::Create("https://api.github.com/repos/" . $repo . "/releases");
        $request->SetAccept("application/vnd.github.v3.raw+json");
        $request->SetUserAgent("horsedrowner.net");
        $response = $request->GetResponse();
        $data = json_decode($response, true);
        return new GitHubRelease($data);
    }
}
