<?php

class LoginController extends Controller
{
    protected $tumblr;

    public function __construct()
    {
        $this->tumblr = new League\OAuth1\Client\Server\Tumblr([
            'identifier'   => 'niEgnSf2pMSHBMv8LLh4h7Bm8nIcd6DcFXdLCIDIqpeMxRj9pY',
            'secret'       => 'GvIYzIjuMWWH5RZSOY7BkgAmBsJtxGUFhFEKrdzGBwkDEjHssG',
            'callback_uri' => "http://iwant.nl/login/return/tumblr"
        ]);
    }

    /**
     * Show login providers
     *
     * @return \Illuminate\View\View
     */
    public function startAction()
    {
        return View::make('login');
    }

    /**
     * Setup the authorize url and redirect the user
     *
     * @param $providerName string
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authorizeAction($providerName)
    {
        $temporaryCredentials = $this->tumblr->getTemporaryCredentials();

        Session::all('temporary_credentials', serialize($temporaryCredentials));

        $this->tumblr->authorize($temporaryCredentials);
    }

    /**
     * Handle the return action from the Oauth provider
     *
     * @param $providerName string
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnAction($providerName)
    {
        $temporaryCredentials = unserialize(Session::get('temporary_credentials'));

        dd(Session::get('temporary_credentials'));

        $tokenCredentials = $this->tumblr->getTokenCredentials($temporaryCredentials, Input::get('oauth_token'), Input::get('oauth_verifier'));

        $user = $this->tumblr->getUserDetails($tokenCredentials);

        dd($user);

        return Redirect::route('dashboard');
    }

    /**
     * Build the provider based upon app/config/providers.php
     *
     * @param $providerName string
     * @param $config array
     * @return mixed
     */
    private function getProvider($providerName, array $config = null)
    {
        $config = $config ?: $this->getProviderConfig($providerName);

        $provider = new $config['class'](array(
            'clientId'     => $config['clientId'],
            'clientSecret' => $config['clientSecret'],
            'redirectUri'  => $config['redirectUri']
        ));

        return $provider;
    }

    /**
     * Get the provider config by provider name
     *
     * @param $providerName
     * @return array
     */
    private function getProviderConfig($providerName)
    {
        return Config::get('providers.' . $providerName);
    }
}