<?php

namespace Amp\Http\Client\Psr7\Internal;

use Amp\ByteStream\InputStream;
use Psr\Http\Message\StreamInterface;

/**
 * @internal
 */
final class PsrInputStream implements InputStream
{
    private const DEFAULT_CHUNK_SIZE = 8192;

    private StreamInterface $stream;

    private int $chunkSize;

    private bool $tryRewind = true;

    public function __construct(StreamInterface $stream, int $chunkSize = self::DEFAULT_CHUNK_SIZE)
    {
        if ($chunkSize < 1) {
            throw new \Error("Invalid chunk size: {$chunkSize}");
        }

        $this->stream = $stream;
        $this->chunkSize = $chunkSize;
    }

    public function read(): ?string
    {
        if (!$this->stream->isReadable()) {
            return null;
        }

        if ($this->tryRewind) {
            $this->tryRewind = false;

            if ($this->stream->isSeekable()) {
                $this->stream->rewind();
            }
        }

        if ($this->stream->eof()) {
            return null;
        }

        return $this->stream->read($this->chunkSize);
    }
}
