<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Entity\Location;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class LocationController extends FOSRestController
{
    /**
     * @Rest\View()
     * @SWG\Get(
     *     tags={"Location"},
     *     summary="Get all location resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Location::class))),
     *     @SWG\Response(response="200", description="Returned all location resource",
     *                                   @SWG\Schema(type="array",
     *                                   @Model(type=Location::class)))
     * )
     */
    public function getLocationsAction()
    {
        $locations = $this->getDoctrine()->getRepository('AppBundle:Location')->findAll();

        return $locations;
    }

    //* @Rest\NoRoute()

    /**
     * @Rest\View(statusCode=201)
     * @ParamConverter("location", converter="fos_rest.request_body")
     * @SWG\Post(
     *     tags={"Location"},
     *     summary="Add a new location resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Location::class))),
     *     @SWG\Response(response="201", description="Returned when resource
     *                                   created", @SWG\Schema(type="array",
     *                                   @Model(type=Location::class))),
     *     @SWG\Response(response="400", description="Missing or invalid parameter value."),
     *     @SWG\Response(response="406", description="Invalid German ZIP code!")
     * )
     */
    public function postLocationAction(Location $location, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new \AppBundle\Exception\ValidationException($validationErrors);
        }

        $zipPattern = '/(?!01000|99999)(0[1-9]\d{3}|[1-9]\d{4})/i';
        if (!preg_match($zipPattern, $location->getZip())) {
            throw new HttpException(406, 'Invalid German ZIP code!');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($location);
        $em->flush();

        return $location;
    }

    /**
     * @Rest\View()
     * @SWG\Delete(
     *     tags={"Location"},
     *     summary="Delete the location resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Location::class))),
     *     @SWG\Response(response="204", description="The server has successfully deleted the resource."),
     *     @SWG\Response(response="400", description="Missing or invalid parameter value."),
     * )
     */
    public function deleteLocationAction(?Location $location)
    {
        if (null === $location) {
            throw new HttpException(404, 'Not Found!');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($location);
        $em->flush();
    }

    /**
     * @Rest\View()
     * @SWG\Get(
     *     tags={"Location"},
     *     summary="Get the location resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Location::class))),
     *     @SWG\Response(response="200", description="Returned when resource find.",
     *                                   @SWG\Schema(type="array",
     *                                   @Model(type=Location::class))),
     *     @SWG\Response(response="404", description="The resource could not find.")
     * )
     */
    public function getLocationAction(?Location $location)
    {
        if (null === $location) {
            throw new HttpException(404, 'Not Found!');
        }

        return $location;
    }

    /**
     * @Rest\View
     * @Rest\Get(path="/locations/zip/{zip}")
     * @SWG\Get(
     *     tags={"Location"},
     *     summary="Get the city name by ZIP code",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Location::class))),
     *     @SWG\Response(response="200", description="Returned when resource find.",
     *                                   @SWG\Schema(type="array",
     *                                   @Model(type=Location::class))),
     *     @SWG\Response(response="404", description="The resource could not find.")
     * )
     */
    public function getLocationZipAction(?Location $location)
    {
        if (null === $location) {
            throw new HttpException(404, 'The ZIP code was not found.');
        }

        return $location;
    }
}
