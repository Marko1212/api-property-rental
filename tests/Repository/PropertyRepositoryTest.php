<?php

namespace App\Tests\Repository;

use App\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertyRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetChosenPropertiesCreatedByManagers()
    {
        $properties = $this->entityManager
            ->getRepository(Property::class)
            ->getChosenPropertiesCreatedByManagers();

        $this->assertSame(array($this->entityManager->getRepository(Property::class)->find(212)), $properties);
    }

    public function testGetChosenPropertyNamesCreatedByAdmins()
    {
        $propertyNames = $this->entityManager
            ->getRepository(Property::class)
            ->getChosenPropertyNamesCreatedByAdmins();

        $this->assertSame([['name' => 'Appartement meublé 4 pièces'], ['name' => 'Maison à louer 5 pièces']], $propertyNames);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
