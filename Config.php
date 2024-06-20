<?php


class Config
{
    private array|false $config;

    public function __construct($filename)
    {
        $this->config = parse_ini_file($filename);
    }

    public function get($key)
    {
        return $this->config[$key] ?? null;
    }
}
