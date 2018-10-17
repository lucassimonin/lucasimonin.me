<?php

namespace App\Controller\Media;

use App\Entity\Media;
use App\Services\Core\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends Controller
{
    /**
     * @param Request           $request
     * @param SerializerService $serializerService
     * @Route("/media/config/seo", name="media_seo")
     * @return Response
     */
    public function seo(Request $request, SerializerService $serializerService)
    {
        $ids = $request->get('ids', null);
        $response = new Response();
        $response->setStatusCode(400);
        if (empty($ids)) {
            return $response;
        }

        $medias = $this->getDoctrine()->getRepository(Media::class)->findByIds(explode(',', $ids));

        $tabs = [];
        /** @var Media $media */
        foreach ($medias as $media) {
            $tabs[] = array(
                'id' => $media->getId(),
                'url' => $this->get('router')->generate('media_view', ['id' => $media->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'title' => $media->getTitle(),
                'extension' => $media->getExtension(),
                'mimetype' => $media->getMimetype(),
                'alt' => $media->getAlt(),
            );
        }

        $response->setStatusCode(200);
        $response->setContent($serializerService->serializeObjectToJson($tabs));

        return $response;
    }

    /**
     * Return media by id
     * @Route("/media/{id}", name="media_view")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function view(Request $request, $id)
    {
        /** @var Media $media */
        $media = $this->getDoctrine()->getRepository(Media::class)->findOneBy(['id' => $id]);
        if (!$media) {
            throw $this->createNotFoundException();
        }

        $media->setFileSystem($this->get('knp_gaufrette.filesystem_map')->get('current'));
        $filename = $media->getWebPath("Media");

        $response = new Response();
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-length', filesize($filename));
        $response->headers->addCacheControlDirective('must-revalidate', true);

        if ($request->get('forceddl', 0) == 1) {
            $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
        } else {
            $response->headers->set(
                'Content-Disposition',
                'inline; filename="' . basename($filename) . '";'
            );
        }

        $response->sendHeaders();

        $response->setContent(file_get_contents($filename));
        $response->setPublic();
        $response->setSharedMaxAge(3600);
        $response->setMaxAge(3600);
        $date = new \DateTime();
        $response->setLastModified($date);


        return $response;
    }
}
