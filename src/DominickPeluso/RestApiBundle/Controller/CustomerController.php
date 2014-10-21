<?php

namespace DominickPeluso\RestApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DominickPeluso\RestApiBundle\Entity\Customer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class CustomerController extends Controller
{
    public function getSingleAction($customer_id)
    {
        $customer = $this->getDoctrine()->getRepository("DominickPelusoRestApiBundle:Customer")->find($customer_id);
        $name = $customer->getName();
        $address1 = $customer->getAddress1();
        $address2 = $customer->getAddress2();
        $city = $customer->getCity();
        $state = $customer->getState();
        $zip = $customer->getZip();
        $country = $customer->getCountry();

        // Send a response
        $response = new Response();
        $response->setContent("<response><customerId>$customer_id</customerId><name>$name</name><address1>$address1</address1><address2>$address2</address2><city>$city</city><state>$state</state><zip>$zip</zip><country>$country</country></response>");
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/xml');

        // prints the HTTP headers followed by the content
        return $response;
    }
    public function updateAction(Request $request, $customer_id)
    {
        $customer = $this->getDoctrine()->getRepository("DominickPelusoRestApiBundle:Customer")->find($customer_id);

        if(empty($customer)){
            // Return error
            $response = new Response();
            $response->setContent("<response><error>Customer ID \"$customer_id\" could not be found</error></response>");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->headers->set('Content-Type', 'application/xml');

            // prints the HTTP headers followed by the content
            return $response;
        }


        // Get a bunch of variables from Request
        $name = $request->request->get('name');
        if(!empty($name)){
            $customer->setName($name);
        }


        // Push all that into Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->persist($customer);
        $em->flush($customer);

        // Send a response
        $response = new Response();
        $response->setContent("<response><customerId>$customer_id</customerId><name>$name</name></response>");
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/xml');

        // prints the HTTP headers followed by the content
        return $response;
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
    public function deleteAction($customer_id)
    {
        $customer = $this->getDoctrine()->getRepository("DominickPelusoRestApiBundle:Customer")->find($customer_id);

        if(empty($customer)){
            // Return error if I can't find a customer with that ID
            $response = new Response();
            $response->setContent("<response><error>Customer ID \"$customer_id\" could not be found</error></response>");
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->headers->set('Content-Type', 'application/xml');
            return $response;
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($customer);
        $em->flush();

        // Send a response
        $response = new Response();
        $response->setContent("<response><customerId>$customer_id</customerId><message>Customer deleted.</message></response>");
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/xml');

        // prints the HTTP headers followed by the content
        return $response;
    }
    public function getManyAction($start, $limit)
    {
        $customers = $this->getDoctrine()->getRepository("DominickPelusoRestApiBundle:Customer")->findBy(
            array(),
            array(),
            $limit,
            $start
        );

        // Loop and create a response XML string
        $responseXML = '<response>';
        foreach($customers as $customer)
        {
            $responseXML .= '<customer>';
            $responseXML .= '<name>'.$customer->getName().'</name>';
            $responseXML .= '<id>'.$customer->getId().'</id>';
            $responseXML .= '</customer>';
        }
        $responseXML .= '</response>';

        // Send a response
        $response = new Response();
        $response->setContent($responseXML);
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/xml');

        return $response;
    }
    public function clientGetCustomersAction()
    {
        $r = new Request();
        $r->create('172.31.47.86/E90-A07_PHP-SYMFONY-REST/web/app.php/rest/client/get-customers', 'GET');

        // Send a response
        $response = new Response();
        $response->setContent($r->getContent());
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
