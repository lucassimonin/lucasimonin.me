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
 * Class CacheManager
 * `
 * cache manager
 *
 * @package App\Services\Core
 */
class CacheManager implements CacheManagerInterface
{
    const CACHE_FOLDER = 'app';
    /** @var FilesystemAdapter  */
    private $cache;
    /** @var Filesystem  */
    private $fileSystem;
    /** @var string  */
    private $cacheFolder;

    /**
     * CacheManager constructor.
     *
     * @param $lifeTime
     * @param $cacheFolder
     */
    public function __construct(int $lifeTime, string $cacheFolder)
    {
        $this->cache = new FilesystemAdapter(self::CACHE_FOLDER, $lifeTime, $cacheFolder);
        $this->cacheFolder = $cacheFolder . '/' . self::CACHE_FOLDER;
        $this->fileSystem = new Filesystem();
    }

    /**
     * @return FilesystemAdapter
     */
    public function getCache(): FilesystemAdapter
    {
        return $this->cache;
    }

    /**
     * Clear cache
     */
    public function clearCache(): void
    {
        $this->cache->clear();
        $this->cache->prune();
        $this->fileSystem->remove($this->cacheFolder);
    }
}
