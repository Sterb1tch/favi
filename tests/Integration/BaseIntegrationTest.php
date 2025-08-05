<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Kernel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

#[DoesNotPerformAssertions]
class BaseIntegrationTest extends KernelTestCase
{
    protected const string EXAMPLE_FILE_CREATE_REQUEST_FIRST = 'examples/request_create_first.json';
    protected const string EXAMPLE_FILE_CREATE_REQUEST_SECOND = 'examples/request_create_second.json';
    protected const string EXAMPLE_FILE_UPDATE_DELIVERY_DATE = 'examples/request_update_delivery_date.json';

    protected SerializerInterface $serializer;
    protected ?EntityManagerInterface $entityManager = null;

    public static function getValidRequestFiles(): array
    {
        return [
            [self::EXAMPLE_FILE_CREATE_REQUEST_FIRST],
            [self::EXAMPLE_FILE_CREATE_REQUEST_SECOND],
        ];
    }

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var SerializerInterface $serializer */
        $serializer = self::getContainer()->get(SerializerInterface::class);
        $this->serializer = $serializer;

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->entityManager = $entityManager;

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    protected function getObject(string $inputJson, string $className): object
    {
        return $this->serializer->deserialize($inputJson, $className, 'json');
    }

    protected function getFileContent(string $filePath, array $placeholders = []): string
    {
        $fileContent = (string)file_get_contents($filePath);

        foreach ($placeholders as $placeholder => $value) {
            $fileContent = str_replace($placeholder, (string)$value, $fileContent);
        }

        return $fileContent;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->entityManager === null) {
            return;
        }

        $this->entityManager->close();
        $this->entityManager = null;
    }

    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }
}
