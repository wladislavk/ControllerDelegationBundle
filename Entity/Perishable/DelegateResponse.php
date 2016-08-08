<?php
namespace VKR\ControllerDelegationBundle\Entity\Perishable;

class DelegateResponse
{
    /**
     * @var array
     */
    protected $viewData = [];

    /**
     * @var string
     */
    protected $urlToRedirect;

    /**
     * @var string
     */
    protected $routeToRedirect;

    /**
     * @var array
     */
    protected $routeParams;

    /**
     * @param array $viewData
     * @return DelegateResponse
     */
    public function setViewData(array $viewData)
    {
        $this->viewData = $viewData;
        return $this;
    }

    public function getViewData()
    {
        return $this->viewData;
    }

    /**
     * @param string $urlToRedirect
     * @return DelegateResponse
     */
    public function setUrlToRedirect($urlToRedirect)
    {
        $this->urlToRedirect = $urlToRedirect;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrlToRedirect()
    {
        return $this->urlToRedirect;
    }

    /**
     * @param string|array $routeToRedirect
     * @param array $routeParams
     * @return DelegateResponse
     */
    public function setRouteToRedirect($routeToRedirect, array $routeParams = [])
    {
        if (is_array($routeToRedirect)) {
            $this->routeToRedirect = $routeToRedirect[0];
            $this->routeParams = $routeToRedirect[1];
            return $this;
        }
        $this->routeToRedirect = $routeToRedirect;
        $this->routeParams = $routeParams;
        return $this;
    }

    /**
     * @return string
     */
    public function getRouteToRedirect()
    {
        return $this->routeToRedirect;
    }

    /**
     * @return array
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }
}
