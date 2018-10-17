<?php

namespace App\Twig;

use App\Entity\Media;

class InjectExtension extends \Twig_Extension
{
    protected $container;
    protected $notify;

    /**
     * Constructor inject fileSystem
     *
     * @param $fileSystem
     */
    public function __construct($fileSystem)
    {
        $this->fileSystem = $fileSystem->get('current');
    }

    /**
     * Expose filters
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('ifs', array($this, 'injectFileSystem')),
        );
    }

    /**
     * Inject File system in object Media
     *
     * @param $obj
     * @return mixed
     */
    public function injectFileSystem($obj)
    {
        if ($obj instanceof Media) {
            $obj->setFileSystem($this->fileSystem);
        }

        return $obj;
    }
}
