<?php

/**
 * Service
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Services\Content;

use App\Entity\ContentInterface;
use App\Services\Core\CacheManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ContentManager
 * `
 * @package App\Services\Content
 */
class ContentManager implements ContentManagerInterface
{
    private $em;

    private $cache;

    /**
     * ContentManager constructor.
     * @param EntityManagerInterface $em
     * @param CacheManagerInterface $cacheManager
     */
    public function __construct(EntityManagerInterface $em, CacheManagerInterface $cacheManager)
    {
        $this->em = $em;
        $this->cache = $cacheManager->getCache();
    }

    /**
     * @param $content
     * @return mixed
     */
    public function save(ContentInterface $content): ContentInterface
    {
        // Save user
        $this->em->persist($content);
        $this->em->flush();

        return $content;
    }

    /**
     * @param mixed $content
     */
    public function remove(ContentInterface $content): void
    {
        $this->em->remove($content);
        $this->em->flush();
    }

    /**
     * @param string $class
     * @param string $cacheKey
     * @param array $filters
     * @param array $orderBy
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getContents(string $class, string $cacheKey, array $filters = [], array $orderBy = [])
    {
        $contentCache = $this->cache->getItem($cacheKey);
        if (!$contentCache->isHit()) {
            $contents = $this->em->getRepository($class)->findContents($filters, $orderBy);
            $contentCache->set($contents);
            $this->cache->save($contentCache);
        } else {
            $contents = $contentCache->get();
        }

        return $contents;
    }
}
