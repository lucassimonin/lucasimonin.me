<?php

/**
 * Service
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Services\Core;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

interface CacheManagerInterface
{
    public function getCache(): FilesystemAdapter;
    public function clearCache(): void;
}
