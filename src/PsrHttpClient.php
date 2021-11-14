<?php

namespace Amp\Http\Client\Psr7;

use Amp\CancellationToken;
use Amp\Http\Client\HttpClient;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface as PsrRequest;
use Psr\Http\Message\ResponseInterface as PsrResponse;

final class PsrHttpClient implements ClientInterface
{
    private HttpClient $httpClient;
    private PsrAdapter $psrAdapter;

    public function __construct(HttpClient $client, PsrAdapter $psrAdapter)
    {
        $this->httpClient = $client;
        $this->psrAdapter = $psrAdapter;
    }

    /**
     * @param PsrRequest             $request
     * @param CancellationToken|null $cancellation
     *
     * @return PsrResponse
     */
    public function sendRequest(PsrRequest $request, ?CancellationToken $cancellation = null): PsrResponse
    {
        $internalRequest = $this->psrAdapter->fromPsrRequest($request);

        $response = $this->httpClient->request($internalRequest, $cancellation);

        return $this->psrAdapter->toPsrResponse($response);
    }
}
