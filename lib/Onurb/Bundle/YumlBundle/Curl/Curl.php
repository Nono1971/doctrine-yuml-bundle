<?php

namespace Onurb\Bundle\YumlBundle\Curl;

class Curl implements CurlInterface
{
    private $curl;

    /**
     * @param string $url
     * @param resource $curl to inject an external curl_id (returned by curl_init)... for testing or whatever
     */
    public function __construct($url, $curl = null)
    {
        $this->curl = $curl ? $curl : curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $url);

        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * @param array $posts
     */
    public function setPosts(array $posts)
    {
        curl_setopt($this->curl, CURLOPT_POST, count($posts));
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query(array_map("utf8_encode", $posts)));
    }

    /**
     * Write the response to a file
     *
     * @param string $filename
     */
    public function setOutput($filename)
    {
        curl_setopt($this->curl, CURLOPT_FILE, fopen($filename, 'w+'));
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getResponse()
    {
        $return = curl_exec($this->curl);
        if ($return === false) {
            throw new \Exception(curl_error($this->curl));
        }

        curl_close($this->curl);
        $this->curl = null;

        return $return;
    }
}
