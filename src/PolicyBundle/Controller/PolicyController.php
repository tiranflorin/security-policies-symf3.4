<?php

namespace PolicyBundle\Controller;

use PolicyBundle\Entity\Module;
use PolicyBundle\Entity\Policy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class PolicyController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction() {
        return new JsonResponse('Policy Management REST api');
    }

    /**
     * @Route("/policy",  methods={"POST"}, name="create_policy")
     */
    public function createPolicyAction(Request $request)
    {
        $module = new Module();
        $module->setName('firewall');
        $module->setStatus(true);

        $policy = new Policy();
        $content = json_decode($request->getContent());

        if (property_exists($content, 'name')) {
            if (!empty($content->name)) {
                $policy->setName($content->name);
            }
        }

        if (property_exists($content, 'implicit_action')) {
            if (!empty($content->implicit_action)) {
                $policy->setImplicitAction($content->implicit_action);
            }
        }
        $policy->setModules(array($module));

        $em = $this->getDoctrine()->getManager();
        $em->persist($module);
        $em->persist($policy);
        $em->flush();

        return new JsonResponse('Saved new product with id '.$module->getId(), Response::HTTP_CREATED);
    }

    /**
     * TODO: Needs pagination
     *
     * @Route("/policy",  methods={"GET"}, name="get_policies")
     */
    public function getPoliciesAction(SerializerInterface $serializer)
    {
        $policy = $this->getDoctrine()
            ->getRepository(Policy::class)
            ->findAll();

        if (!$policy) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $serializedData = $serializer->serialize(
            $policy,
            'json', array('groups' => array('rest'))
        );

        return JsonResponse::fromJsonString($serializedData);
    }

    /**
     * @Route("/policy/{id}",  methods={"GET"}, name="get_policy_by_id", requirements={"$id"="\d+"})
     */
    public function getPolicyAction($id, SerializerInterface $serializer)
    {
        $policy = $this->getDoctrine()
            ->getRepository(Policy::class)
            ->find($id);

        if (!$policy) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $serializedData = $serializer->serialize(
            $policy,
            'json', array('groups' => array('rest'))
        );

        return JsonResponse::fromJsonString($serializedData);
    }

    /**
     * @Route("/policy/{id}",  methods={"DELETE"}, name="delete_policy_by_id", requirements={"$id"="\d+"})
     */
    public function deletePolicyAction($id)
    {
        $policy = $this->getDoctrine()
            ->getRepository(Policy::class)
            ->find($id);

        if (!$policy) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($policy);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * @Route("/policy/{id}",  methods={"PUT"}, name="update_policy_by_id", requirements={"$id"="\d+"})
     */
    public function updatePolicyAction(Request $request, $id)
    {
        /** @var Policy $policy */
        $policy = $this->getDoctrine()
            ->getRepository(Policy::class)
            ->find($id);

        if (!$policy) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $content = json_decode($request->getContent());

        if (property_exists($content, 'name')) {
            if (!empty($content->name)) {
                $policy->setName($content->name);
            }
        }

        if (property_exists($content, 'implicit_action')) {
            if (!empty($content->implicit_action)) {
                $policy->setImplicitAction($content->implicit_action);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse();
    }
}
