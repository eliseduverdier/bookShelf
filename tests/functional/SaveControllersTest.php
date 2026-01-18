<?php

declare(strict_types=1);

namespace App\Tests\functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class SaveControllersTest extends WebTestCase
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

    public function testNewBook(): void
    {
        $crawler = $this->client->request('GET', '/');

        $this->client->submitForm('add', [
            'title' => 'My New Book',
            'author' => 'Author 1',
            'type' => '1',
            'note' => '2',
            'finished_at' => '2023-01-01',
        ]);
        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertSelectorTextContains('li#user1-my-new-book-author-1 .title-item', 'My New Book');
        self::assertSelectorTextContains('li#user1-my-new-book-author-1 .finished-at-item', '2023⋅01⋅01');
    }

    public function testDeleteBook(): void
    {
        $this->client->request('GET', '/book/user1_author1_title3/delete');
        self::assertResponseRedirects('/');

        $this->client->followRedirect();
        self::assertSelectorNotExists('#user1_author1_title3');
    }
}
