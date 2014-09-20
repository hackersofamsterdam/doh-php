<?php

use League\OAuth2\Client\Provider\AbstractProvider;

class Wordpress extends AbstractProvider implements LoginProviderInterface
{
    public function urlAuthorize()
    {
        return 'https://public-api.wordpress.com/oauth2/authorize';
    }

    public function urlAccessToken()
    {
        return 'https://public-api.wordpress.com/oauth2/token';
    }

    public function urlUserDetails(\League\OAuth2\Client\Token\AccessToken $token)
    {
        return 'https://public-api.wordpress.com/rest/v1/me';
    }

    public function userDetails($response, \League\OAuth2\Client\Token\AccessToken $token)
    {
        return [
            'blog_user_id' => $response->ID,
            'name'         => $response->display_name,
            'username'     => $response->username,
            'email'        => $response->email,
            'avatar'       => $response->avatar_URL,
            'blog_id'      => $response->primary_blog,
            'language'     => $response->language,
            'access_token' => $token
        ];
    }

    public function setHeaders(array $headers, $token = null)
    {
        foreach($headers as $key => $val)
        {
            $this->headers[$key] = str_replace('{token}', $token, $val);
        }
    }
}