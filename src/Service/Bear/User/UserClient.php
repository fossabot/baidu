<?php

/*
 * This file is part of the Easeava package.
 *
 * (c) Easeava <tthd@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EaseBaidu\Service\Bear\User;

use EaseBaidu\Kernel\BaseClient;

class UserClient extends BaseClient
{

    /**
     * Get user by openid.
     *
     * @param $openID
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($openID)
    {
        $params = [
            'user_list' => is_string($openID) ? [[
                'openid' => $openID
            ]] : array_map(function ($item) {
                return [
                    'openid' => $item,
                ];
            }, $openID) ,
        ];
        var_dump($params);
        return $this->httpPostJson('/rest/2.0/cambrian/user/info', $params);
    }

    /**
     * List users.
     *
     * @param string|null $start_index
     * @return array|\EaseBaidu\Kernel\Http\Response|\GuzzleHttp\Psr7\MessageTrait|\Illuminate\Support\Collection|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \EaseBaidu\Kernel\Exceptions\InvalidArgumentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list(string $start_index = null)
    {
        return $this->httpGet('/rest/2.0/cambrian/user/get', ['start_index' => $start_index]);
    }
}