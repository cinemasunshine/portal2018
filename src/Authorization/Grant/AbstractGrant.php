<?php

/**
 * AbstractGrant.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

declare(strict_types=1);

namespace App\Authorization\Grant;

use GuzzleHttp\Client as HttpClient;

/**
 * Abstract Grant class
 */
abstract class AbstractGrant
{
    /**
     * create HTTP client
     *
     * @param string $baseUri
     * @return HttpClient
     */
    protected function createHttpClient(string $baseUri): HttpClient
    {
        $config = [
            'base_uri'        => $baseUri,
            'timeout'         => 5, // ひとまず5秒
            'connect_timeout' => 5, // ひとまず5秒
            'http_errors'     => true,
        ];

        return new HttpClient($config);
    }

    /**
     * return request headers
     *
     * @param string $clientId
     * @param string $clientSecret
     * @return array
     */
    protected function getRequestHeaders(string $clientId, string $clientSecret): array
    {
        $encodedCredentials = base64_encode(sprintf('%s:%s', $clientId, $clientSecret));

        $headers = [
            'Authorization' => 'Basic ' . $encodedCredentials,
            'Content-Type'  => 'application/x-www-form-urlencoded',
        ];

        return $headers;
    }
}
