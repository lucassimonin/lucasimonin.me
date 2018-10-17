<?php

/**
 * Service
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Services\Core;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class UserService
 * `
 * Object manager of user
 *
 * @package App\Services\Core
 */
class CacheService
{
    const CACHE_FOLDER = 'app';
    private $cache;
    private $fileSystem;
    private $cacheFolder;

    /**
     * CacheService constructor.
     *
     * @param $lifeTime
     * @param $cacheFolder
     */
    public function __construct($lifeTime, $cacheFolder)
    {
        $this->cache = new FilesystemAdapter(self::CACHE_FOLDER, $lifeTime, $cacheFolder);
        $this->cacheFolder = $cacheFolder . '/' . self::CACHE_FOLDER;
        $this->fileSystem = new Filesystem();
    }


    /**
     * @return FilesystemAdapter
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        $this->cache->clear();
        $this->cache->prune();
        $this->fileSystem->remove($this->cacheFolder);
    }
}
