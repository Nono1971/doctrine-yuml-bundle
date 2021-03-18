<?php
namespace Onurb\Bundle\YumlBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class DoctrineYumlDataCollector extends DataCollector
{
    public function collect(Request $request, Response $response, ?\Throwable $exception = null)
    {
        $this->data = array('doctrine_yuml' => array());
    }

    public function getName()
    {
        return 'doctrine_yuml';
    }

    public function reset()
    {
        $this->data = array();
    }
}
