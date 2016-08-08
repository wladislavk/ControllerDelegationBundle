<?php
namespace VKR\ControllerDelegationBundle\TestFixtures;

use VKR\ControllerDelegationBundle\Delegates\AbstractDelegate;
use VKR\ControllerDelegationBundle\Entity\Perishable\DelegateResponse;

class TestDelegate extends AbstractDelegate
{
    protected $requiredRedirectRoutes = [
        'success',
    ];

    public function viewDataAction()
    {
        $data = [
            'foo' => 'bar',
        ];
        return $this->delegateResponse->setViewData($data);
    }

    public function redirectToUrlAction()
    {
        $url = 'http://someurl.com';
        return $this->delegateResponse->setUrlToRedirect($url);
    }

    public function redirectToRouteAction()
    {
        $route = 'my-route';
        $params = [
            'foo' => 'bar',
        ];
        return $this->delegateResponse->setRouteToRedirect($route, $params);
    }

    public function responsePrecedenceAction()
    {
        $data = [
            'foo' => 'bar',
        ];
        $this->delegateResponse->setViewData($data);
        $url = 'http://someurl.com';
        $this->delegateResponse->setUrlToRedirect($url);
        $route = 'my-route';
        $this->delegateResponse->setRouteToRedirect($route);
        return $this->delegateResponse;
    }

    public function requiredRoutesAction()
    {
        return $this->delegateResponse
            ->setRouteToRedirect($this->redirectRoutes['success']);
    }

    public function flashMessageAction()
    {
        $data = [
            'foo' => 'bar',
        ];
        $this->controller->addFlash(self::FLASH_SUCCESS, 'success!');
        return $this->delegateResponse->setViewData($data);
    }
}
