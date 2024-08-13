<?php
declare(strict_types=1);

namespace WHMCS\Module\Server\AzuraCast;

use WHMCS\Module\Server\AzuraCast\Dto;
use GuzzleHttp\Psr7\Uri;

class Client extends AbstractClient
{
    public function admin(): AdminClient
    {
        return new AdminClient($this->httpClient);
    }

    /**
     * @param string $host
     * @param string|null $apiKey
     * @param array|null $options Additional GuzzleHttp client options.
     *
     * @return Client
     */
    public static function create(
        string $host,
        ?string $apiKey = null,
        ?array $options = null
    ): self {
        if (null === $options) {
            $options = [];
        }

        $baseUri = new Uri($host);
        if (empty($baseUri->getScheme())) {
            $baseUri = $baseUri->withScheme('http');
        }
        $baseUri = $baseUri->withPath('/api/');

        $options['base_uri'] = (string)$baseUri;

        $options['allow_redirects'] = true;
        $options['http_errors'] = false;
        $options['headers']['accept'] = 'application/json';


        if (null !== $apiKey) {
            $options['headers']['Authorization'] = 'Bearer ' . $apiKey;
        }

        $httpClient = new \GuzzleHttp\Client($options);
        return new self($httpClient);
    }
}
