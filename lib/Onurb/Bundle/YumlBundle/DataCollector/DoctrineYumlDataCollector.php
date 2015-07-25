<?php
namespace Onurb\Bundle\yumlBundle\DataCollector;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class DoctrineYumlDataCollector extends DataCollector
{
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array('doctrine_yuml' => array());
    }

    public function getName()
    {
        return 'doctrine_yuml';
    }
}
