<?php
class HttpWebRequest
{
    protected function __construct() { }

    public $url = "";
    public $method = "GET";
    public $headers = [];

    public static function Create($url)
    {
        $request = new HttpWebRequest();
        $request->url = $url;
        return $request;
    }

    public function SetAccept($value)
    {
        $this->headers["Accept"] = $value;
    }

    public function SetUserAgent($value)
    {
        $this->headers["User-Agent"] = $value;
    }

    public function GetResponse()
    {
        $options = [
            "http" => [
                "method" => $this->method,
                "header" => $this->GetHeader(),
            ]
        ];

        Debug::WriteLine("Requesting " . $this->url . "...");
        Debug::Dump($options);
        $context = stream_context_create($options);
        try
        {
            return file_get_contents($this->url, false, $context);
        }
        catch (Exception $e)
        {
            Debug::WriteLine(implode($http_response_header));
            Debug::WriteLine($e);
        }
    }

    protected function GetHeader()
    {
        $header = "";
        foreach ($this->headers as $key => $value)
        {
            $header .= $key . ": " . $value . "\n";
        }
        return $header;
    }
}
