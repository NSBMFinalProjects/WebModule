<?php
namespace App\Controller\OAuth;

use App\Errors\GithubAccessToken;
use App\Utils\OAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/oauth/github/redirect', name: 'github_redirect')]
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

    #[Route('/oauth/github/callback', name: 'github_callback')]
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

        // TODO: Add The user data to the database and generate a token indicating that the user is properly logged in
        // with the platfrom
        echo "<h1><u>User details</u></h1>";
        echo "The ID is       : " . $user['id'] . '<br>';
        echo "The username is : " . $user['login'] . '<br>';
        echo "The PhotoURL is : " . $user['avatar_url'] . '<br>';
        echo "The name is     : " . $user['name'] . '<br>';

        return new Response(null, Response::HTTP_OK);
    }
}
