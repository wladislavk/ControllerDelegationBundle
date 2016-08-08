<?php
namespace VKR\ControllerDelegationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use VKR\ControllerDelegationBundle\Entity\Perishable\DelegateResponse;

abstract class AbstractDelegatedController extends Controller
{
    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return parent::has($id);
    }

    /**
     * {@inheritDoc}
     */
    public function get($id)
    {
        return parent::get($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getParameter($name) {
        return parent::getParameter($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getUser()
    {
        return parent::getUser();
    }

    /**
     * {@inheritDoc}
     */
    public function getDoctrine()
    {
        return parent::getDoctrine();
    }

    /**
     * {@inheritDoc}
     */
    public function createForm($type, $data = null, array $options = [])
    {
        return parent::createForm($type, $data, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function addFlash($type, $message)
    {
        parent::addFlash($type, $message);
    }

    /**
     * {@inheritDoc}
     */
    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return parent::generateUrl($route, $parameters, $referenceType);
    }

    /**
     * @param DelegateResponse $response
     * @return array|RedirectResponse
     */
    protected function parseDelegateResponse(DelegateResponse $response)
    {
        if ($response->getUrlToRedirect()) {
            return $this->redirect($response->getUrlToRedirect());
        }
        if ($response->getRouteToRedirect()) {
            return $this->redirectToRoute($response->getRouteToRedirect(), $response->getRouteParams());
        }
        return $response->getViewData();
    }
}
