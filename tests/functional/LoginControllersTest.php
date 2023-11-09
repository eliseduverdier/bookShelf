<?php

namespace App\Tests\functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class LoginControllersTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testRedirectToLogin(): void
    {
        $urlsNotAllowedUnauthentified = [
            '/', 'book/user1_author1_title1', '/settings', '/statistics'
        ];
        foreach ($urlsNotAllowedUnauthentified as $url) {
            $this->client->request('GET', $url);
            self::assertEquals('http://localhost/login', $this->client->getResponse()->headers->get('location'));
        }
        // ?? below not working, has meta-refresh ?? should be 302 ??
        // self::assertResponseRedirects('/login');
    }


    public function testListUsers(): void
    {
        $crawler = $this->client->request('GET', '/users');
        self::assertResponseIsSuccessful();
        self::assertEquals(2, $crawler->filter('.user-list__item')->count());
    }

    public function testShowUsersBooks(): void
    {
        $crawler = $this->client->request('GET', '/user/user2');
        self::assertResponseIsSuccessful();
        self::assertEquals(2, $crawler->filter('.books-list__item')->count());
        self::assertSelectorExists('#user2_author2_title2');
        self::assertSelectorNotExists('#user1_author1_title1');
    }

    public function testIsLogged(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('.user-open-menu', 'Hello user1 !');
    }
}
