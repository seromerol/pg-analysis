<?php 

class Google extends CI_Controller { 

public function __construct() { 

} 

/** * Welcome index * * @access     public */ 

public function index() { 
$provider = new third_party/src/Provider/google([
    'clientId'      => '118760729273-v7r5c3l0t0f1kk49crl7cjabnmr5agdj.apps.googleusercontent.com',
    'clientSecret'  => 'Ubh3NkNq27BKuyTGx-IKNdZ_',
    'redirectUri'   => 'https://grado.gnu-media.org/oauth2callback',
    'scopes'        => ['email', '...', '...'],
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->state;
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $userDetails = $provider->getUserDetails($token);
        $data=$userDetails;

        // Use these details to create a new profile
        printf('Hello %s!', $userDetails->firstName);

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    //echo $token->accessToken;

    // Use this to get a new access token if the old one expires
    //echo $token->refreshToken;

    // Number of seconds until the access token will expire, and need refreshing
    //echo $token->expires;
}
$this->load->view('google', $data); 
} 

 
} 
?>