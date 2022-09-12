<?php

namespace App\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
    private array $serverInformations = ['ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NjI5Njk0MzIsImV4cCI6MTY2Mjk3MzAzMiwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6IkxlZHVjLlZhbGVyaWVAVmFpbGxhbnQub3JnIn0.VFO9JePeoreCSojtwL-fmM3hYqKDlJaxtZx30fvmTWtrhShJv-nJeJGdpjxDkQPAQr9IcO9qX7uK1RlXjQ9P154BBEz26wwT1B8jmHb7mDaifgyZ3Mc00HCCNm8P71HLRiHK3le8NYpTBBSBCknwc34TbOoEYMI_xh5mu6D-_dGrHlQKTggqIfwVu2qfOAFolh7feRYJ3uD_7wJkmntmwylbJjunbSVMft5fQ9OXj-wMaumfJoheNKCJmcOpwTZ84AD0wjLVjWGropTgepom6YhgiCGDSsHCCYATqefdSp_aRwVOmQ-yIB66soYTNgYk1zcfFtcP8cq3UqDp6a5quw'];

    public function getResponseFromRequest(string $method, string $uri, string $payload = ''): Response
    {
        $client = self::createClient();
        $client->request($method, $uri . '.json', [], [], $this->serverInformations, $payload);
        return $client->getResponse();
    }
}
