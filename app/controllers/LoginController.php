<?php

class LoginController extends Controller
{
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
        $provider = $this->getProvider($providerName);

        return Redirect::to($provider->getAuthorizationUrl());
    }

    /**
     * Handle the return action from the Oauth provider
     *
     * @param $providerName string
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnAction($providerName)
    {
        $config   = $this->getProviderConfig($providerName);
        $provider = $this->getProvider($providerName, $config);

        $token = $provider->getAccessToken('authorization_code', [
            'code' => Input::get('code')
        ]);

        $provider->setHeaders($config['headers'], $token);

        $userDetails             = $provider->getUserDetails($token);
        $userDetails['provider'] = $providerName;

        if (!$user = User::where('provider', '=', $providerName)->where('blog_user_id', '=', $userDetails['blog_user_id'])->first()) {
            $user = User::create($userDetails);
        }

        Auth::login($user);

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