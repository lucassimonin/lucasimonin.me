<?php

namespace App\Twig;

use App\Entity\Media;
use Twig\Environment;

class FrontExtension extends \Twig_Extension
{
    private $twig;
    /**
     * FrontExtension constructor.
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    /**
     * Expose filters
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('media_url', array($this, 'mediaUrl')),
        );
    }

    /**
     * @param Media $media
     * @param string $class
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function mediaUrl(Media $media, string $class = '') : string
    {
        return $this->twig->render('front/parts/media.html.twig', [
            'media' => $media,
            'class' => $class
        ]);
    }
}
