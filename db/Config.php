<?php

class Config
{
    private array $config;

    public function __construct($filename)
    {
        $this->config = parse_ini_file($filename, true);
    }

    public function get($section, $key)
    {
        return $this->config[$section][$key] ?? null;
    }
}
