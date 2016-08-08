<?php
namespace VKR\ControllerDelegationBundle\TestFixtures;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use VKR\ControllerDelegationBundle\Controller\AbstractDelegatedController;

class TestController extends AbstractDelegatedController
{
    /**
     * @var array
     */
    public $flashMessages;

    public function viewDataAction()
    {
        $redirectRoutes = [
            '_default' => 'default-route',
        ];
        $delegate = new TestDelegate($this, $redirectRoutes);
        $response = $delegate->viewDataAction();
        return $this->parseDelegateResponse($response);
    }

    public function redirectToUrlAction()
    {
        $redirectRoutes = [
            '_default' => 'default-route',
        ];
        $delegate = new TestDelegate($this, $redirectRoutes);
        $response = $delegate->redirectToUrlAction();
        return $this->parseDelegateResponse($response);
    }

    public function redirectToRouteAction()
    {
        $redirectRoutes = [
            '_default' => 'default-route',
        ];
        $delegate = new TestDelegate($this, $redirectRoutes);
        $response = $delegate->redirectToRouteAction();
        return $this->parseDelegateResponse($response);
    }

    public function responsePrecedenceAction()
    {
        $redirectRoutes = [
            '_default' => 'default-route',
        ];
        $delegate = new TestDelegate($this, $redirectRoutes);
        $response = $delegate->responsePrecedenceAction();
        return $this->parseDelegateResponse($response);
    }

    public function redirectToRequiredRoutesSuccessAction()
    {
        $redirectRoutes = [
            'success' => 'success-route',
        ];
        $delegate = new TestDelegate($this, $redirectRoutes);
        $response = $delegate->requiredRoutesAction();
        return $this->parseDelegateResponse($response);
    }

    public function redirectToRequiredRoutesArrayAction()
    {
        $redirectRoutes = [
            'success' => [
                'success-route',
                ['foo' => 'bar'],
            ],
        ];
        $delegate = new TestDelegate($this, $redirectRoutes);
        $response = $delegate->requiredRoutesAction();
        return $this->parseDelegateResponse($response);
    }

    public function redirectToRequiredRoutesFailAction()
    {
        $redirectRoutes = [
            'fail' => [
                'fail-route',
            ]
        ];
        $delegate = new TestDelegate($this, $redirectRoutes);
        $response = $delegate->requiredRoutesAction();
        return $this->parseDelegateResponse($response);
    }

    public function redirectToDefaultRouteAction()
    {
        $redirectRoutes = [
            '_default' => 'default-route',
        ];
        $delegate = new TestDelegate($this, $redirectRoutes);
        $response = $delegate->requiredRoutesAction();
        return $this->parseDelegateResponse($response);
    }

    public function flashMessageAction()
    {
        $redirectRoutes = [
            '_default' => 'default-route',
        ];
        $delegate = new TestDelegate($this, $redirectRoutes);
        $response = $delegate->flashMessageAction();
        return $this->parseDelegateResponse($response);
    }

    public function addFlash($type, $message)
    {
        $this->flashMessages[] = [
            $type => $message,
        ];
    }

    public function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $url = 'http://' . $route . '.com';
        if (sizeof($parameters)) {
            $parametersString = '?';
            $i = 0;
            foreach ($parameters as $name => $value) {
                if ($i) {
                    $parametersString .= '&';
                }
                $parametersString .= $name . '=' . $value;
                $i++;
            }
            $url .= $parametersString;
        }
        return $url;
    }
}
