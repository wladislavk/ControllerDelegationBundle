About
=====

This small bundle enables usage of the Delegation pattern in Symfony controllers.
Its main use-case is when you create a reusable component that requires some controller
logic but does not require to define complete controllers and templates. It can be said
that the goal of this bundle is to provide a system of bundle inheritance that is more
lightweight and hassle-free than default Symfony bundle inheritance.

This bundle consists just of three classes - two abstract and one concrete. It does not
do anything by itself, and is designed to be used as a dependency in other reusable
bundles.

This bundle has no dependencies other than Symfony itself.

Philosophy
==========

Suppose you have some reusable code that involves a lot of manipulation with the
container and invoking other useful controller methods such as ```createForm()```.
Of course, you can put these into an actual controller, but then you also have to
define a view. Both controller actions and views can be overridden by bundle inheritance,
but that involves following a naming convention, and that can be inconvenient. For
example, customizing ```FOSUserBundle``` is not the easiest thing in the world.

The philosophy behind this bundle states that it will be easier for a client coder
to invoke a delegate method inside each controller action that would accept the same
arguments as the actual controller but might return something other than HTTP response.
The controller then parses the results of delegate's work in whatever way it deems
necessary.

Thus, it can be said that the actual controller acts as a decorator for the delegate
class.

Usage
=====

Create a delegate class that should extend ```VKR\ControllerDelegationBundle\Delegates\AbstractDelegate```.
This class is container-aware and has access to all controller methods via ```$this->controller```.
It should return the same instance of ```DelegateResponse``` class that was created
in its parent constructor under the name of ```$this->delegateResponse```.

Use it in the following way:

```
public function myDelegatedAction($someArgument)
{
    ...
    $viewData = [
        'templateVar' => 'value',
    ];
    return $this->delegateResponse->setViewData($viewData);
}
```

All setter methods of ```DelegateResponse``` return its instance. You could also write

```
$this->delegateResponse->setViewData($viewData);
return $this->delegateResponse;
```

Then it is expected that the client coder will create a controller that will extend
```VKR\ControllerDelegationBundle\Controller\AbstractDelegatedController``` and write
the following method in it:

```
public function myDelegatedAction($someArgument)
{
    $delegate = new MyDelegate($this);
    $delegateResponse = $delegate->myDelegatedAction($someArgument);
    $parsedResponse = $this->parseDelegateResponse($delegateResponse);
    return $this->render('my/template.html.twig', $parsedResponse);
}
```

```parseDelegateResponse()``` is a shortcut for getting template variables. It is
generally recommended that both controller method and delegate method have same
signatures.

Dealing with redirects
----------------------

If there are any redirects that you wish to return, things become more tricky. The
concept is that the delegate class creator should not hardcode any route names.
These are replaced with special markers, such as 'success' or 'failure'. Then you
do something like this in your delegate class:

```
return $this->delegateResponse->setRouteToRedirect($this->redirectRoutes['success']);
```

For these markers to be parsed, they must be first declared as ```$requiredRedirectRoutes```
class property:

```
protected $requiredRedirectRoutes = ['success'];
```

Note that required routes list can be neither declared nor redefined in action methods.

Then, this should be added to your controller:

```
$redirectRoutes = ['success' => 'my_success_route'];
$delegate = new MyDelegate($this, $redirectRoutes);
...
return $this->parseDelegateResponse($delegateResponse);
```

If your delegate can return both redirect and non-redirect values, the controller
will look as follows:

```
$parsedResponse = $this->parseDelegateResponse($delegateResponse);
if ($parsedResponse instanceof RedirectResponse) {
    return $parsedResponse;
}
return $this->render('my/template.html.twig', $parsedResponse);
```

If the ```DelegateResponse``` object contains both redirect and non-redirect values,
redirect will take precedence.

If your route comes with parameters, you can modify your controller as follows:

```
$redirectRoutes = ['success' => ['my_success_route', ['foo' => 'bar']]];
```

Finally, if you want to skip some of required parameters, you can use ```_default```
key:

```
$redirectRoutes = ['_default' => 'my_default_route'];
```

Sometimes, you will want to use external links for redirect, e.g. an API call. Here
is how you do it:

```
#Delegate
return $this->delegateResponse->setUrlToRedirect('https://api.facebook.com');
```

Dealing with additional render calls
------------------------------------

Suppose that inside your controller logic you need to render something that is not going to be
returned by the controller. The best example here is sending emails. In this case,
you just need to pass an extra argument to the delegate action, and then parse it
somehow in your delegate:

```
#Controller
public function sendEmailAction()
{
    $emailData = [
        'subject' => 'My subject',
        'template' => 'my/email/template.html.twig',
        'templateVars' => [
            'foo' => 'bar'
        ],
    ];
    $delegate = new RegistrationDelegate($this);
    $delegateResponse = $delegate->myDelegatedAction($emailData);
}
```

Inside your delegate you can use

```
$this->controller->render($emailData['template'], $emailData['templateVars']);
```

Note that template name and template variables should not be hardcoded inside
the delegate class.

Best practices
==============

- Put your delegate classes in a separate folder and append ```Delegate``` to their
class names.
- Use one delegate method per every controller method. The names of delegate method
and corresponding controller method should be identical.
- Do not initialize the delegate object inside your controller's constructor or any
method called by constructor. While not causing any errors by itself, using the constructor
might cause bugs in the future, if you invoke some container methods from the delegate's
constructor. In Symfony, the container gets filled with contents AFTER the controller
object is created.
- Do not put business logic in your delegate classes and try to keep them skinny.
- Do not unit-test your delegate classes. Delegate classes should only keep controller
logic in them, and controller logic is not unit-testable.
