<?php
namespace App\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class GaufretteListener
 *
 * @package App\Listener
 */
class GaufretteListener
{
    /**
     * Const Trait Need to activate listener
     */
    const TRAIT_GAUFRETTE = 'App\\Traits\\GaufretteTrait';

    // Attribute for file system gaufrette
    public $fileSystem = null;

    /**
     * GaufretteListener constructor.
     *
     * @param null $fileSystem
     */
    public function __construct($fileSystem)
    {
        $this->fileSystem = $fileSystem->get('current');
    }

    /**
     * Set file system to redisturb
     *
     * @param $fileSystem
     */
    public function setFileSystem($fileSystem)
    {
        $this->fileSystem = $fileSystem->get('current');
    }



    /**
     * On post load
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $this->tryEnabled($args);
    }

    /**
     * On pre update
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->tryEnabled($args);
    }

    /**
     * On pre persist
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->tryEnabled($args);
    }

    /**
     * On pre remove
     *
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->tryEnabled($args);
    }

    /**
     * Try enabled
     *
     * @param LifecycleEventArgs $args
     */
    public function tryEnabled(LifecycleEventArgs $args)
    {
        if (in_array(self::TRAIT_GAUFRETTE, class_uses($args->getEntity())) or
        (new \ReflectionClass($args->getEntity()))->getShortName() == "Media") {
            $args->getEntity()->setFileSystem($this->fileSystem);
            if ($args->getEntity()->isFirstPreUpload()) {
                $args->getEntity()->preUploads();
            }
        }
    }
}
