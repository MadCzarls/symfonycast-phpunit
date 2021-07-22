<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\LoadBasicParkData;
use App\DataFixtures\LoadSecurityData;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

use function sprintf;

class AppControllerTest extends WebTestCase
{
    use FixturesTrait;

    private AbstractDatabaseTool $databaseTool;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->databaseTool = static::getContainer()->get('liip_test_fixtures.services.database_tool_collection')
            ->get(testCase: $this);
    }

    public function testEnclosuresAreShownOnHomepage(): void
    {
        $this->databaseTool->loadFixtures([LoadBasicParkData::class, LoadSecurityData::class]);

        $crawler = $this->client->request(Request::METHOD_GET, '/');
        $table = $crawler->filter('.table-enclosures');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertCount(3, $table->filter('tbody tr'));
    }

    public function testThereIsAnAlarmButtonForEnclosuresWithoutSecurity(): void
    {
        $fixtures = $this->databaseTool
            ->loadFixtures([LoadBasicParkData::class, LoadSecurityData::class])
            ->getReferenceRepository();

        $crawler = $this->client->request(Request::METHOD_GET, '/');
        $enclosureWithoutActiveSecurity = $fixtures->getReference('carnivorous-enclosure');
        $selector = sprintf('#enclosure-%d .button-alarm', $enclosureWithoutActiveSecurity->getId());

        $this->assertGreaterThan(0, $crawler->filter($selector)->count());
    }
}
