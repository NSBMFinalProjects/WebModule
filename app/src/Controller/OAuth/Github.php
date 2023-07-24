<?php
namespace App\Controller\OAuth;

use App\Enums\Provider;
use App\Models\User;
use App\Utils\OAuth;
use App\Utils\Token;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Github extends AbstractController
{
    private $authorizeURL = "https://github.com/login/oauth/authorize"; 
    private $tokenURL = "https://github.com/login/oauth/access_token"; 
    private $apiURLBase = "https://api.github.com"; 

    private $clientID;
    private $clientSecret;
    private $redirectURL;

    public function __construct()
    {
        $this->clientID = $_ENV["GITHUB_CLIENT_ID"];
        $this->clientSecret = $_ENV["GITHUB_CLIENT_SECRET"];
        $this->redirectURL = $_ENV["GITHUB_CALLBACK_URL"];
    }

    #[Route(name: 'github_redirect')]
    public function github(): Response
    { 
        $request = Request::createFromGlobals();
        $state = $request->query->get('state');

        $options = [
        'client_id' => $this->clientID,
        'redirect_uri' => $this->redirectURL,
        'scope' => ['user:email'],
        'state' => $state
        ];

        $url = $this->authorizeURL . '?' . http_build_query($options);
        return $this->redirect($url);
    }

    #[Route(name: 'github_callback')]
    public function callback(): Response
    {
        $request = Request::createFromGlobals();

        $code = $request->query->get("code");
        $state = $request->query->get("state");

        if ($code == "") {
            return $this->redirectToRoute(route: 'home');
        }

        if ($state == "") {
            $state = $_ENV["DOMAIN"];
        }

        $access_token = OAuth::getGithubAccessToken($this->clientID, $this->clientSecret, $state, $code);
        if ($access_token == "") {
            return $this->redirectToRoute('github_redirect');
        }

        $user = OAuth::getGithubUser($access_token);
        if ($user == null) {
            return $this->redirectToRoute('home');
        }

        $username = $user['login'];
        $display_name = $user['name'];
        $photo_url = $user['avatar_url'];
        $email = $user['email'] ? $user['email'] : null;
        $provider = Provider::GITHUB->value;
        $provider_id = $user['id'];

        try {
            $newUser = new User;
            if ($newUser->checkUsername($username)) {
                $newUser->setUser($username, $display_name, $photo_url, $provider, $provider_id, $email);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return new Response(null, $e->getCode());
        }

        $jwt = Token::create($username);

        $res = new Response();
        $cookie = new Cookie(
            name: 'session',
            value: $jwt,
            expire: 0,
            path: '/',
            domain: 'localhost',
            secure: false,
            httpOnly: true
        );
        $res->headers->setCookie($cookie);
        $res->sendHeaders();

        return $this->redirectToRoute(route: 'home');
    }
}
