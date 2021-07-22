<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class AppControllerTest extends WebTestCase
{
    public function testEnclosuresAreShownOnHomepage(): void
    {
        $client = $this->makeClient();
        $crawler = $client->request(Request::METHOD_GET, '/');

        $table = $crawler->filter('.table-enclosures');
        $this->assertCount(3, $table->filter('tbody tr'));

        $this->assertStatusCode(200, $client);

    }
}
