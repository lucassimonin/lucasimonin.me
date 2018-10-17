<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\Type\Media\MediaType;
use App\Services\Core\SerializerService;
use App\Services\Media\MediaService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class MediaController
 *
 * @package App\Controller\Admin
 * @Route("/{_locale}/admin/media", requirements={ "_locale" = "%admin.locales%" })
 */
class MediaController extends Controller
{
    /**
     * @param Media   $media
     * @Route("/info/{id}", name="media_info")
     * @return JsonResponse
     */
    public function infoAction(Media $media)
    {
        return new JsonResponse(array(
            'id' => $media->getId(),
            'url' => $this->get('router')->generate('media_view', ['id' => $media->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'title' => $media->getTitle(),
            'extension' => $media->getExtension(),
            'mimetype' => $media->getMimetype(),
            'alt' => $media->getAlt(),
        ));
    }

    /**
     * @param Request      $request
     * @param Media        $media
     * @Route("/seo/{id}", name="image_seo")
     * @return JsonResponse|Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function seoMedia(Request $request, Media $media)
    {
        // Create form
        $form = $this->createForm(MediaType::class, $media);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $media = $this->get(MediaService::class)->save($media);

                return new JsonResponse(array(
                    'id' => $media->getId(),
                    'url' => $this->get('router')->generate('media_view', ['id' => $media->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                    'title' => $media->getTitle(),
                    'alt' => $media->getAlt(),
                ));
            }
        }

        return new Response($this->renderView('admin/partials/mediaForm.html.twig', array(
            'form' => $form->createView(),
            'id' => $media->getId()
        )));
    }

    /**
     * @param Request           $request
     * @param MediaService      $mediaService
     * @param SerializerService $serializerService
     * @Route("/upload/media", name="admin_media_upload")
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function index(Request $request, MediaService $mediaService, SerializerService $serializerService)
    {
        $response = new Response();
        $response->setStatusCode(400);

        // Get File to upload
        $file = $request->files->get('file', null);
        if (!$file) {
            return $response;
        }

        $media = new Media();
        $media->setFileMedia($file);
        $media = $mediaService->save($media);

        if ($media) {
            $response->setStatusCode(200);
            $tab = array(
                'id' => $media->getId(),
                'url' => $this->get('router')->generate('media_view', ['id' => $media->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'title' => $media->getTitle(),
                'alt' => $media->getAlt(),
            );
            $response->setContent($serializerService->serializeObjectToJson($tab));
        }

        return $response;
    }


    /**
     * @param Media        $media
     * @param MediaService $mediaService
     * @Route("/media-delete/{id}", name="media_delete")
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteAction(Media $media, MediaService $mediaService)
    {
        $mediaService->remove($media);

        return new Response();
    }



    /**
     * @Route("/media-add", name="media_add")
     *
     * @return JsonResponse|Response
     */
    public function addAction()
    {
        return new Response($this->renderView('admin/media/partials/add.html.twig', array()));
    }
}
