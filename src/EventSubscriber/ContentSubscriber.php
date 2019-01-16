<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 16/04/2018
 * Time: 18:03
 */

namespace App\EventSubscriber;

use App\Entity\ContentInterface;
use App\Services\Core\CacheManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ContentSubscriber
{
    /** @var ContentInterface */
    private $entity;

    /** @var EntityManager */
    private $entityManager;

    private $cache;

    /** @var array */
    private $locales;

    /**
     * ContentSubscriber constructor.
     * @param CacheManagerInterface $cacheManager
     * @param $locales
     */
    public function __construct(CacheManagerInterface $cacheManager, $locales)
    {
        $this->cache = $cacheManager->getCache();
        $this->locales = $locales;
    }


    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate'
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->entity = $args->getEntity();
        $this->entityManager = $args->getEntityManager();
        if (!$this->isAvailable()) {
            return;
        }
        $this->removeCache();
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->entity = $args->getEntity();
        $this->entityManager = $args->getEntityManager();
        if (!$this->isAvailable()) {
            return;
        }
        $this->removeCache();
    }



    private function removeCache()
    {
        foreach ($this->locales as $locale) {
            $this->cache->deleteItem($this->entity->keyCache() . $locale);
        }
    }

    private function isAvailable()
    {
        return $this->entity instanceof ContentInterface;
    }
}
