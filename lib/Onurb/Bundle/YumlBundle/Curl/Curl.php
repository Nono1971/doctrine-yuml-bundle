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
        $this->curl = $curl? $curl: curl_init();
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
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->formatCurlPostsString($posts));
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

    /**
     * @param array $posts
     * @return string
     */
    private function formatCurlPostsString(array $posts)
    {
        $tmp = array();
        foreach ($posts as $key => $post) {
            $tmp[] = $this->formatCurlPostItem($key, $post);
        }
        return implode('&', $tmp);
    }

    /**
     * @param string $key
     * @param string $value
     * @return string
     */
    private function formatCurlPostItem($key, $value)
    {
        return $key . '=' . urlencode($value);
    }
}
