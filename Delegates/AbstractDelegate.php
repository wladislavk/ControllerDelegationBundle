<?php
namespace VKR\ControllerDelegationBundle\Delegates;

use Symfony\Component\Form\AbstractType;
use VKR\ControllerDelegationBundle\Controller\AbstractDelegatedController;
use VKR\ControllerDelegationBundle\Entity\Perishable\DelegateResponse;
use VKR\ControllerDelegationBundle\Exception\UndefinedRouteException;

abstract class AbstractDelegate
{
    const FLASH_SUCCESS = 'vkr_oauth_user_success';
    const FLASH_FAILURE = 'vkr_oauth_user_failure';
    const DEFAULT_ROUTE_NAME = '_default';

    /**
     * @var AbstractDelegatedController
     */
    protected $controller;

    /**
     * @var DelegateResponse
     */
    protected $delegateResponse;

    /**
     * @var string[]
     */
    protected $requiredRedirectRoutes = [];

    /**
     * @var array
     */
    protected $redirectRoutes;

    /**
     * @param AbstractDelegatedController $controller
     * @param array $redirectRoutes
     */
    public function __construct(AbstractDelegatedController $controller, array $redirectRoutes = [])
    {
        $this->controller = $controller;
        $this->delegateResponse = new DelegateResponse();
        $this->setRedirectRoutes($redirectRoutes);
    }

    /**
     * @param array $routes
     * @throws UndefinedRouteException
     */
    protected function setRedirectRoutes(array $routes)
    {
        if (is_array($this->requiredRedirectRoutes)) {
            foreach ($this->requiredRedirectRoutes as $routeName) {
                if (!isset($routes[$routeName])) {
                    if (!isset($routes[self::DEFAULT_ROUTE_NAME])) {
                        throw new UndefinedRouteException(
                            "Either \$routes['$routeName'] or \$routes['" . self::DEFAULT_ROUTE_NAME . "'] must be set"
                        );
                    }
                    $this->redirectRoutes[$routeName] = $routes[self::DEFAULT_ROUTE_NAME];
                    continue;
                }
                $this->redirectRoutes[$routeName] = $routes[$routeName];
            }
        }
    }
}
