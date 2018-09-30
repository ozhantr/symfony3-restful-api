<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Entity\Job;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\EntityMerger\EntityMerger;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class JobController extends FOSRestController
{
    /**
     * @var EntityMerger
     */
    private $entityMerger;

    /**
     * @param EntityMerger $entityMerger
     */
    public function __construct(EntityMerger $entityMerger)
    {
        $this->entityMerger = $entityMerger;
    }

    /**
     * @Rest\View()
     * @SWG\Get(
     *     tags={"Job"},
     *     summary="Get all job resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Job::class))),
     *     @SWG\Response(response="200", description="Returned all job resource",
     *                                   @SWG\Schema(type="array",
     *                                   @Model(type=Job::class)))
     * )
     */
    public function getJobsAction()
    {
        $jobs = $this->getDoctrine()->getRepository('AppBundle:Job')->findAll();

        return $jobs;
    }

    /**
     * @Rest\View(statusCode=201)
     * @ParamConverter("job", converter="fos_rest.request_body")
     * @SWG\Post(
     *     tags={"Job"},
     *     summary="Add a new job resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Job::class))),
     *     @SWG\Response(response="201", description="Returned when resource
     *                                   created", @SWG\Schema(type="array",
     *                                   @Model(type=Job::class))),
     *     @SWG\Response(response="400", description="Missing or invalid parameter value."),
     *     @SWG\Response(response="406", description="Invalid German ZIP code!")
     * )
     */
    public function postJobAction(Job $job, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new \AppBundle\Exception\ValidationException($validationErrors);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($job);
        $em->flush();

        return $job;
    }

    /**
     * @Rest\View()
     * @SWG\Delete(
     *     tags={"Job"},
     *     summary="Delete the job resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Job::class))),
     *     @SWG\Response(response="204", description="The server has successfully deleted the resource."),
     *     @SWG\Response(response="400", description="Missing or invalid parameter value."),
     * )
     */
    public function deleteJobAction(?Job $job)
    {
        if (null === $job) {
            return $this->view(null, 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($job);
        $em->flush();
    }

    /**
     * @Rest\View()
     * @SWG\Get(
     *     tags={"Job"},
     *     summary="Get the job resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Job::class))),
     *     @SWG\Response(response="200", description="Returned when resource find.",
     *                                   @SWG\Schema(type="array",
     *                                   @Model(type=Job::class))),
     *     @SWG\Response(response="404", description="The resource could not find.")
     * )
     */
    public function getJobAction(?Job $job)
    {
        if (null === $job) {
            throw new HttpException(404, 'Not Found!');
        }

        return $job;
    }

    /**
     * @ParamConverter("modifiedJob", converter="fos_rest.request_body")
     * @SWG\Patch(
     *     tags={"Job"},
     *     summary="Add a new job resource",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(name="body", in="body", required=true,
     *                                 @SWG\Schema(type="array", @Model(type=Job::class))),
     *     @SWG\Response(response="200", description="Returned when the resource updated.",
     *                                   @SWG\Schema(type="array",
     *                                   @Model(type=Job::class))),
     *     @SWG\Response(response="400", description="Missing or invalid parameter value."),
     *     @SWG\Response(response="404", description="The resource could not find.")
     * )
     */
    public function patchJobAction(?Job $job, Job $modifiedJob, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new \AppBundle\Exception\ValidationException($validationErrors);
        }

        if (null === $job) {
            throw new HttpException(404, 'Not Found!');
        }

        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        // Merge entities
        $this->entityMerger->merge($job, $modifiedJob);

        // Persist
        $em = $this->getDoctrine()->getManager();
        $em->persist($job);
        $em->flush();

        // Return
        return $job;
    }
}
