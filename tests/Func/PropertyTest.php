<?php

namespace App\Tests\Func;

use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PropertyTest extends AbstractEndPoint
{
    private string $propertyPayload = '{"name": "%s",
        "city": "Valenciennes",
        "street": "rue Gérard de Perfontaine",
        "price": 500,
        "numberOfRooms": 3,
        "status": "active"}';

    public function testGetProperties(): void
    {
        $response = $this->getResponseFromRequest(Request::METHOD_GET, '/api/properties');
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    public function testPostProperty(): void
    {
        $response = $this->getResponseFromRequest(Request::METHOD_POST, '/api/properties', $this->getPayload());
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    private function getPayload()
    {
        $faker = Factory::create('fr_FR');
        return sprintf($this->propertyPayload, $faker->sentence(10));
    }
}
