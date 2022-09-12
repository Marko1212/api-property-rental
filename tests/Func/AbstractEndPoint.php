<?php

namespace App\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
    private array $serverInformations = ['ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json', 'HTTP_AUTHORIZATION' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NjI5NjUyMTYsImV4cCI6MTY2Mjk2ODgxNiwicm9sZXMiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJ1c2VybmFtZSI6IkxlZHVjLlZhbGVyaWVAVmFpbGxhbnQub3JnIn0.mrunV4zyF8I17Tpq8ayFrd5t_IB58k8uE_egg_Di7FpakQVe5nrWf4lAvgIoV0OwPIJtgtVEs81AzMexfxkq3rJns6Aeo8cO6p0f29u6Ci21NB9u_OFa5P_Wiz11e4_gS3LKrI68aKRSew0xnbcNYaHAAKC5LBerd7225zMo1nVScTL_zkzvyov7GKY-jWO_8wKB-ee9PepSpInWU2JgVAwlD6maLMRc7VRinZ8lkfLpxn8ss7HUWuD40pYMM8PsTehRj-AtDDtn7VSzeoRUmZ2CA_UO_MqBgzSZM0Eg5qFK2A7Wi0Z_49ha2S-OhFalb9CBJ5aJTXzkJdD9Gh-zGA'];

    public function getResponseFromRequest(string $method, string $uri, string $payload = ''): Response
    {
        $client = self::createClient();
        $client->request($method, $uri . '.json', [], [], $this->serverInformations, $payload);
        return $client->getResponse();
    }
}
