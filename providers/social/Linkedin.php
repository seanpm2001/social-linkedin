<?php
/**
 * @link      https://dukt.net/craft/linkedin/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/linkedin/docs/license
 */

namespace Dukt\Social\LoginProviders;

use Craft\UrlHelper;
use Guzzle\Http\Client;

class Linkedin extends BaseProvider
{
    // Public Methods
    // =========================================================================

	/**
	 * Get Name
	 */
	public function getName()
    {
        return "LinkedIn";
    }

    /**
     * Get Scopes
     */
    public function getScopes()
    {
        return [
            'user'
        ];
    }

    /**
     * Get Profile
     */
    public function getProfile()
    {
        $fields = [
            'id', 'email-address', 'first-name', 'last-name', 'headline', 'location', 'industry', 'picture-url', 'public-profile-url',
        ];

        $fields = implode(',', $fields);

        $response = $this->api('get', 'people/~:(' . $fields . ')');

        return [
            'id' => $response['id'],
            'email' => $response['emailAddress'],
            'username' => $response['emailAddress'],
            'photo' => $response['pictureUrl'],
            'firstName' => $response['firstName'],
            'lastName' => $response['lastName'],
            'profileUrl' => $response['pictureUrl'],
            'location' => $response['location']['name'],
        ];
    }

    private function api($method = 'get', $uri, $params = ['format'=>'json'], $headers = null, $postFields = null)
    {
        // client
        $client = new Client('https://api.linkedin.com/v1/');

        //token
        $token = $this->token;

        // params
        $headers['Authorization'] = 'Bearer '.$token->accessToken;


        // request

        $query = '';

        if($params)
        {
            $query = http_build_query($params);

            if($query)
            {
                $query = '?'.$query;
            }
        }

        $url = $uri.$query;

        $response = $client->get($url, $headers, $postFields)->send();

        $response = $response->json();

        return $response;
    }
}


