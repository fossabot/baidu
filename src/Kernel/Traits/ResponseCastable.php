<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Kernel\Traits;

use EaseBaidu\Kernel\Contracts\Arrayable;
use EaseBaidu\Kernel\Exceptions\InvalidArgumentException;
use EaseBaidu\Kernel\Http\Response;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

trait ResponseCastable
{
    /**
     * @param ResponseInterface $response
     * @param null $type
     * @return array|Response|\Illuminate\Support\Collection|mixed|ResponseInterface
     * @throws InvalidArgumentException
     */
    protected function castResponseToType(ResponseInterface $response, $type = null)
    {
        $response = Response::buildFromPsrResponse($response);
        $response->getBody()->rewind();

        switch ($type ?? 'array') {
            case 'collection':
                return $response->toCollection();
            case 'array':
                return $response->toArray();
            case 'object':
                return $response->toObject();
            case 'raw':
                return $response;
            default:
                if (!is_subclass_of($type, Arrayable::class)) {
                    throw new InvalidArgumentException(sprintf(
                        'Config key "response_type" classname must be an instanceof %s',
                        Arrayable::class
                    ));
                }

                return new $type($response);
        }
    }

    /**
     * @param $response
     * @param null $type
     * @return array|Response|Collection|mixed|ResponseInterface
     * @throws InvalidArgumentException
     */
    protected function detectAndCastResponseToType($response, $type = null)
    {
        switch (true) {
            case $response instanceof ResponseInterface:
                $response = Response::buildFromPsrResponse($response);
                break;
            case $response instanceof Arrayable:
                $response = new Response(200, [], json_encode($response->toArray()));
                break;
            case $response instanceof Collection || is_array($response) || is_object($response):
                $response = new Response(200, [], json_encode($response));
                break;
            case is_scalar($response):
                $response = new Response(200, [], $response);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Unsupported response type "%s"', gettype($response)));
        }

        return $this->castResponseToType($response, $type);
    }
}