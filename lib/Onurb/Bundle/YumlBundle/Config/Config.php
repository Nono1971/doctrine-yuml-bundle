<?php
declare(strict_types=1);

namespace Onurb\Bundle\YumlBundle\Config;

class Config
{
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'show_fields_description' => false,
            'colors' => [],
            'notes' => [],
            'extension' => 'png',
            'style' => 'plain',
            'direction' => 'TB',
            'scale' => 'normal',
        ], $config);
    }

    public function getParameter(string $name)
    {
        if (!array_key_exists($name, $this->config)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid parameter requested. Expected one of: "%s", received "%s".',
                implode('","', array_keys($this->config)),
                $name
            ));
        }

        return $this->config[$name];
    }
}
