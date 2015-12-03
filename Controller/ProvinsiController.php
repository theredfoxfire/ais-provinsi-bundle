<?php

namespace Ais\ProvinsiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Ais\ProvinsiBundle\Exception\InvalidFormException;
use Ais\ProvinsiBundle\Form\ProvinsiType;
use Ais\ProvinsiBundle\Model\ProvinsiInterface;


class ProvinsiController extends FOSRestController
{
    /**
     * List all provinsis.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing provinsis.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many provinsis to return.")
     *
     * @Annotations\View(
     *  templateVar="provinsis"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getProvinsisAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('ais_provinsi.provinsi.handler')->all($limit, $offset);
    }

    /**
     * Get single Provinsi.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Provinsi for a given id",
     *   output = "Ais\ProvinsiBundle\Entity\Provinsi",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the provinsi is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="provinsi")
     *
     * @param int     $id      the provinsi id
     *
     * @return array
     *
     * @throws NotFoundHttpException when provinsi not exist
     */
    public function getProvinsiAction($id)
    {
        $provinsi = $this->getOr404($id);

        return $provinsi;
    }

    /**
     * Presents the form to use to create a new provinsi.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newProvinsiAction()
    {
        return $this->createForm(new ProvinsiType());
    }
    
    /**
     * Presents the form to use to edit provinsi.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisProvinsiBundle:Provinsi:editProvinsi.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the provinsi id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when provinsi not exist
     */
    public function editProvinsiAction($id)
    {
		$provinsi = $this->getProvinsiAction($id);
		
        return array('form' => $this->createForm(new ProvinsiType(), $provinsi), 'provinsi' => $provinsi);
    }

    /**
     * Create a Provinsi from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new provinsi from the submitted data.",
     *   input = "Ais\ProvinsiBundle\Form\ProvinsiType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisProvinsiBundle:Provinsi:newProvinsi.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postProvinsiAction(Request $request)
    {
        try {
            $newProvinsi = $this->container->get('ais_provinsi.provinsi.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newProvinsi->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_provinsi', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing provinsi from the submitted data or create a new provinsi at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\ProvinsiBundle\Form\ProvinsiType",
     *   statusCodes = {
     *     201 = "Returned when the Provinsi is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisProvinsiBundle:Provinsi:editProvinsi.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the provinsi id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when provinsi not exist
     */
    public function putProvinsiAction(Request $request, $id)
    {
        try {
            if (!($provinsi = $this->container->get('ais_provinsi.provinsi.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $provinsi = $this->container->get('ais_provinsi.provinsi.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $provinsi = $this->container->get('ais_provinsi.provinsi.handler')->put(
                    $provinsi,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $provinsi->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_provinsi', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing provinsi from the submitted data or create a new provinsi at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\ProvinsiBundle\Form\ProvinsiType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisProvinsiBundle:Provinsi:editProvinsi.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the provinsi id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when provinsi not exist
     */
    public function patchProvinsiAction(Request $request, $id)
    {
        try {
            $provinsi = $this->container->get('ais_provinsi.provinsi.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $provinsi->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_provinsi', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch a Provinsi or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return ProvinsiInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($provinsi = $this->container->get('ais_provinsi.provinsi.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $provinsi;
    }
    
    public function postUpdateProvinsiAction(Request $request, $id)
    {
		try {
            $provinsi = $this->container->get('ais_provinsi.provinsi.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $provinsi->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_provinsi', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
	}
}
