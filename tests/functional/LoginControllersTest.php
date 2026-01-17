<?php

declare(strict_types=1);

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
            '/', '/settings', '/statistics',
        ];
        foreach ($urlsNotAllowedUnauthentified as $url) {
            $this->client->request('GET', $url);
            self::assertEquals('http://localhost/login', $this->client->getResponse()->headers->get('location'));
        }
    }

    public function testUserLogin(): void
    {
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('.user-menu', 'log in');

        $this->client->submitForm('log in', [
            '_username' => 'user1',
            '_password' => 'un deux trois',
        ]);
        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertSelectorTextContains('.user-open-menu', 'Hello user1 !');
    }

    public function testUserLoginWrongPassword(): void
    {
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('.user-menu', 'log in');

        $this->client->submitForm('log in', [
            '_username' => 'user1',
            '_password' => 'nope',
        ]);
        self::assertSelectorTextContains('div.error p', 'Login error');
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

    public function testLogout(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/logout');
        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        // self::assertResponseRedirects('/login'); // ?
        self::assertEquals('http://localhost/login', $this->client->getResponse()->headers->get('location'));
        $this->client->followRedirect(); // to /login
        self::assertSelectorTextContains('.user-menu', 'log in');
    }
}
