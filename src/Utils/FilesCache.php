<?php


namespace App\Utils;


use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

class FilesCache
{
    public $cache;

    public function __construct()
    {
        $this->cache = new TagAwareAdapter(
            new FilesystemAdapter()
        );
    }
}