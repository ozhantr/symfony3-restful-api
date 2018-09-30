<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Entity\Service;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class ServiceController extends FOSRestController
{
    /**
     * @Rest\View()
     * @SWG\Get(
     *     tags={"Service"},
     *     summary="Get all service resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Service::class))),
     *     @SWG\Response(response="200", description="Returned all service resource",
     *                                   @SWG\Schema(type="array",
     *                                   @Model(type=Service::class)))
     * )
     */
    public function getServicesAction()
    {
        $services = $this->getDoctrine()->getRepository('AppBundle:Service')->findAll();

        return $services;
    }

    /**
     * @Rest\View(statusCode=201)
     * @ParamConverter("service", converter="fos_rest.request_body")
     * @SWG\Post(
     *     tags={"Service"},
     *     summary="Add a new service resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Service::class))),
     *     @SWG\Response(response="201", description="Returned when resource
     *                                   created", @SWG\Schema(type="array",
     *                                   @Model(type=Service::class))),
     *     @SWG\Response(response="400", description="Missing or invalid parameter value.")
     * )
     */
    public function postServiceAction(Service $service, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new \AppBundle\Exception\ValidationException($validationErrors);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($service);
        $em->flush();

        return $service;
    }

    /**
     * @Rest\View()
     * @SWG\Delete(
     *     tags={"Service"},
     *     summary="Delete the service resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Service::class))),
     *     @SWG\Response(response="204", description="The server has successfully deleted the resource."),
     *     @SWG\Response(response="400", description="Missing or invalid parameter value."),
     * )
     */
    public function deleteServiceAction(?Service $service)
    {
        if (null === $service) {
            return $this->view(null, 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($service);
        $em->flush();
    }

    /**
     * @Rest\View()
     * @SWG\Get(
     *     tags={"Service"},
     *     summary="Get the service resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Service::class))),
     *     @SWG\Response(response="200", description="Returned when resource find.",
     *                                   @SWG\Schema(type="array",
     *                                   @Model(type=Service::class))),
     *     @SWG\Response(response="404", description="The resource could not find.")
     * )
     */
    public function getServiceAction(?Service $service)
    {
        if (null === $service) {
            throw new HttpException(404, 'Not Found!');
        }

        return $service;
    }
}
