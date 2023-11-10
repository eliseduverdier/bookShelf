<?php

namespace App\Tests\functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class ListControllersTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        // LOGIN
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $this->client->loginUser($testUser);
    }

    public function testListBooks(): void
    {
        $this->client->request('GET', '/?filter[type]=1&order[note]=asc');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('#user1_author1_title1 .title-item', 'My Title 1');
        self::assertSelectorTextContains('#user1_author1_title1 .finished-at-item', 'currently reading');
    }

    public function testStatistics(): void
    {
        $crawler = $this->client->request('GET', '/statistics');
        self::assertResponseIsSuccessful();
        self::assertEquals(4, $crawler->filter('h2')->count());
    }
}
