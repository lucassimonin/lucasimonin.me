<?php
/**
 * Created by PhpStorm.
 * User: lsimonin
 * Date: 16/04/2018
 * Time: 18:03
 */

namespace App\EventSubscriber;

use App\Entity\Experience;
use App\Entity\Skill;
use App\Entity\Work;
use App\Services\Core\CacheService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ContentSubscriber
{
    /** @var Work|Experience|Skill */
    private $entity;

    /** @var EntityManager */
    private $entityManager;

    private $cache;

    /** @var array */
    private $locales;

    /**
     * ContentSubscriber constructor.
     * @param CacheService $cacheService
     * @param $locales
     */
    public function __construct(CacheService $cacheService, $locales)
    {
        $this->cache = $cacheService->getCache();
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
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
        $key = '';
        switch (get_class($this->entity)) {
            case Work::class:
                $key = 'app.works.';
                break;
            case Experience::class:
                $key = 'app.experiences.';
                break;
            case Skill::class:
                $key = 'app.skills.' . $this->entity->getType() . '.';
                break;
        }
        foreach ($this->locales as $locale) {
            $this->cache->deleteItem($key . $locale);
        }
    }

    private function isAvailable()
    {
        return in_array(get_class($this->entity), [Work::class, Experience::class, Skill::class]);
    }
}
