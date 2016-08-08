<?php
namespace VKR\ControllerDelegationBundle\Tests;

use VKR\ControllerDelegationBundle\Exception\UndefinedRouteException;
use VKR\ControllerDelegationBundle\TestFixtures\TestController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ControllerDelegationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestController
     */
    protected $controller;

    public function setUp()
    {
        $this->controller = new TestController();
    }

    public function testViewData()
    {
        /** @var array $response */
        $response = $this->controller->viewDataAction();
        $this->assertEquals('bar', $response['foo']);
    }

    public function testRedirectToUrl()
    {
        /** @var RedirectResponse $response */
        $response = $this->controller->redirectToUrlAction();
        $this->assertEquals('http://someurl.com', $response->getTargetUrl());
    }

    public function testRedirectWithParams()
    {
        /** @var RedirectResponse $response */
        $response = $this->controller->redirectToRouteAction();
        $this->assertEquals('http://my-route.com?foo=bar', $response->getTargetUrl());
    }

    public function testResponsePrecedence()
    {
        /** @var RedirectResponse $response */
        $response = $this->controller->responsePrecedenceAction();
        $this->assertTrue($response instanceof RedirectResponse);
        $this->assertEquals('http://someurl.com', $response->getTargetUrl());
    }

    public function testRequiredRoutesSuccess()
    {
        /** @var RedirectResponse $response */
        $response = $this->controller->redirectToRequiredRoutesSuccessAction();
        $this->assertEquals('http://success-route.com', $response->getTargetUrl());
    }

    public function testRequiredRoutesSuccessArray()
    {
        /** @var RedirectResponse $response */
        $response = $this->controller->redirectToRequiredRoutesArrayAction();
        $this->assertEquals('http://success-route.com?foo=bar', $response->getTargetUrl());
    }

    public function testRequiredRoutesFail()
    {
        $this->setExpectedException(UndefinedRouteException::class);
        /** @var RedirectResponse $response */
        $response = $this->controller->redirectToRequiredRoutesFailAction();
    }

    public function testDefaultRoute()
    {
        /** @var RedirectResponse $response */
        $response = $this->controller->redirectToDefaultRouteAction();
        $this->assertEquals('http://default-route.com', $response->getTargetUrl());
    }

    public function testFlashMessages()
    {
        /** @var array $response */
        $response = $this->controller->flashMessageAction();
        $expected = ['vkr_oauth_user_success' => 'success!'];
        $this->assertEquals($expected, $this->controller->flashMessages[0]);
    }
}
