<?php

namespace Waraqa\Articles;

use GuzzleHttp\Client as GuzzleClient;

class GetArticle
{
    const REQUEST_URI = 'client-api/articles/content/';

    /**
     * base_uri
     *
     * @var mixed
     */
    private $base_uri;
    
    /**
     * client_access_id
     *
     * @var mixed
     */
    private $client_access_id;
    
    /**
     * client_password
     *
     * @var mixed
     */
    private $client_password;

    /**
     * __construct
     *
     * @param  mixed $base_uri
     * @return void
     */
    public function __construct(String $base_uri, String $client_access_id, String $client_password)
    {
        $this->base_uri = $base_uri;
        $this->client_access_id = $client_access_id;
        $this->client_password = $client_password;
    }

    /**
     * Get the article from waraqa API
     *
     * @param  Int $article_id
     * @return string
     */
    public function fetchSingle(Int $article_id)
    {
        // Create a client with a base URI        
        $client = new GuzzleClient(['base_uri' => $this->base_uri]);
        $token = $this->getClientToken($client);

        $request_uri = self::REQUEST_URI . $article_id;
        // Send a request to $request_uri
        $response = $client->request('GET', $request_uri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $responseContent = json_decode($response->getBody()->getContents());
        return $responseContent->data;
    }

    /**
     * Authenticate waraqa API using client credentials and get token
     *
     * @param  GuzzleClient $client
     * @return string
     */
    public function getClientToken(GuzzleClient $client)
    {
        $response = $client->request(
            'POST',
            'client-api/login',
            [
                'form_params' => [
                    'access_id' => $this->client_access_id,
                    'password' => $this->client_password,
                ]
            ]
        );
        $responseToken = json_decode($response->getBody()->getContents());

        return $responseToken->data->token;
    }
}
