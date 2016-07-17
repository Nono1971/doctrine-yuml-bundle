<?php

namespace Onurb\Bundle\YumlBundle\Curl;

interface CurlInterface
{

    /**
     * @param array $posts
     */
    public function setPosts(array $posts);

    /**
     * Write the response to a file
     *
     * @param string $filename
     */
    public function setOutput($filename);

    /**
     * @return mixed
     */
    public function getResponse();
}
