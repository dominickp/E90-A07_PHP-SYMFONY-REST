<?php

namespace DominickPeluso\RestApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DominickPeluso\RestApiBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CustomerController extends Controller
{
    public function getSingleAction()
    {

    }
    public function updateAction()
    {

    }
    public function createAction(Request $request)
    {
        // Get a bunch of variables from Request
        $name = $request->request->get('name');
        $address1 = $request->request->get('address1');
        $address2 = $request->request->get('address2');
        $city = $request->request->get('city');
        $state = $request->request->get('state');
        $zip = $request->request->get('zip');
        $country = $request->request->get('country');

        // Make a new Customer object
        $customer = new Customer();
        $customer->setName($name);
        $customer->setAddress1($address1);
        $customer->setAddress2($address2);
        $customer->setCity($city);
        $customer->setState($state);
        $customer->setZip($zip);
        $customer->setCountry($country);

        // Push all that into Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->persist($customer);
        $em->flush($customer);

        // Get the customer ID
        $customerId = $customer->getId();

        // Send a response
        $response = new Response();
        $response->setContent("<response><customerId>$customerId</customerId><message>Customer created!</message></response>");
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/xml');

        // prints the HTTP headers followed by the content
        return $response;
    }
}
