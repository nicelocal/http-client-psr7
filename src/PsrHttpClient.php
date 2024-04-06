<?php declare(strict_types=1);

namespace Amp\Http\Client\Psr7;

use Amp\Cancellation;
use Amp\Http\Client\HttpClient;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface as PsrRequest;
use Psr\Http\Message\ResponseInterface as PsrResponse;

final class PsrHttpClient implements ClientInterface
{
    public function __construct(private readonly HttpClient $httpClient, private readonly PsrAdapter $psrAdapter)
    {
    }

    public function sendRequest(PsrRequest $request, ?Cancellation $cancellation = null): PsrResponse
    {
        $internalRequest = $this->psrAdapter->fromPsrRequest($request);

        $response = $this->httpClient->request($internalRequest, $cancellation);

        return $this->psrAdapter->toPsrResponse($response);
    }
}
