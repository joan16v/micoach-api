<?php

namespace Joan\AdidasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

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
            'redirectUri'             => 'http://localhost/micoach-api/symfony/web/app_dev.php/adidas',
            'urlAuthorize'            => 'https://pf.adidas.com/as/authorization.oauth2',
            'urlAccessToken'          => 'https://api.micoach.com/oauth/token',
            'urlResourceOwnerDetails' => 'https://api.micoach.com/v3/users/me'
        ]);
        //print_r($provider);
        //echo $provider->getAuthorizationUrl() . '<br>';
        //echo $provider->getState() . '<br>';

        if (!$request->query->has('code')) {
            $authorizationUrl = $provider->getAuthorizationUrl();
            $session->set('oauth2state', $provider->getState());
            //$request->query->get('code')
            return $this->redirect($authorizationUrl);
        }

        if (!$request->query->has('state') || $request->query->get('state') != $session->get('oauth2state')) {
            return new Response('Invalid state');
        }

        try {

            // Try to get an access token using the authorization code grant.
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $request->query->get('code'),
            ]);

            // We have an access token, which we may use in authenticated
            // requests against the service provider's API.
            echo $accessToken->getToken() . "\n";
            echo $accessToken->getRefreshToken() . "\n";
            echo $accessToken->getExpires() . "\n";
            echo ($accessToken->hasExpired() ? 'expired' : 'not expired') . "\n";

            // Using the access token, we may look up details about the
            // resource owner.
            $resourceOwner = $provider->getResourceOwner($accessToken);

            $result = var_export($resourceOwner->toArray());

            return new Response($result);

        } catch (IdentityProviderException $e) {

            // Failed to get the access token or user details.
            return new Response($e->getMessage());

        }

        //$buzz = $this->container->get('buzz');
        //$response = $buzz->get($provider->getAuthorizationUrl());
        //echo $response->getContent();

        return new Response('');
    }
}
