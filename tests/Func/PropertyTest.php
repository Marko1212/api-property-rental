<?php

namespace App\Tests\Func;

use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PropertyTest extends AbstractEndPoint
{
    private string $propertyPostPayload = '{"name": "%s",
        "city": "Valenciennes",
        "street": "rue Gérard de Perfontaine",
        "price": 500,
        "numberOfRooms": 3,
        "status": "active"}';

    private string $propertyPutPayload = '{
        "street": "Résidence Verley, rue Gérard de Perfontaine",
        "price": 600}';

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

    public function testGetProperty(): void
    {
        $response = $this->getResponseFromRequest(Request::METHOD_GET, '/api/properties/64');
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    public function testPutProperty(): void
    {
        $response = $this->getResponseFromRequest(Request::METHOD_PUT, '/api/properties/64', $this->propertyPutPayload);
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    public function testDeleteProperty(): void
    {
        $response = $this->getResponseFromRequest(Request::METHOD_DELETE, '/api/properties/65');
        $responseContent = $response->getContent();

        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    private function getPayload()
    {
        $faker = Factory::create('fr_FR');
        return sprintf($this->propertyPostPayload, $faker->sentence(10));
    }
}
