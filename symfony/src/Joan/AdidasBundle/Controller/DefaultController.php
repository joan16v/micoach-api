<?php

namespace Joan\AdidasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use League\OAuth2\Client\Provider\GenericProvider;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JoanAdidasBundle:Default:index.html.twig');
    }

    public function adidasAction(Request $request)
    {
        $session = $request->getSession();
        //$session->set('foo', 'bar');
        //$foo = $session->get('foo');

        $provider = new GenericProvider([
            'clientId'                => 'demoapp',    // The client ID assigned to you by the provider
            'clientSecret'            => 'demopass',   // The client password assigned to you by the provider
            'redirectUri'             => 'http://example.com/your-redirect-url/',
            'urlAuthorize'            => 'https://pf.adidas.com/as/authorization.oauth2',
            'urlAccessToken'          => 'https://api.micoach.com/oauth/token',
            'urlResourceOwnerDetails' => 'http://brentertainment.com/oauth2/lockdin/resource'
        ]);
        //print_r($provider);
        echo $provider->getAuthorizationUrl() . '<br>';
        echo $provider->getState() . '<br>';

        $buzz = $this->container->get('buzz');
        $response = $buzz->get($provider->getAuthorizationUrl());
        echo $response->getContent();

        return new Response('');
    }
}
