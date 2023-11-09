<?php

namespace App\Tests\functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class EditControllersTest extends WebTestCase
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

    public function testViewAndEditBook(): void
    {
        $crawler = $this->client->request('GET', '/book/user1_author1_title4/edit');
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('form#edit');
        self::assertSelectorExists('input[name="title"]');
        self::assertEquals('My Title 4', $crawler->filter('input[name="title"]')->attr('value'));

        $this->client->submitForm('OK', [
            'title' => 'My New Title',
            'author' => 'New Author',
            'type' => '1',
            'note' => '2',
            'summary' => 'My new summary',
        ]);
        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertSelectorTextContains('li#user1_author1_title4 .title-item', 'My New Title');
    }

    public function testDeleteBook(): void
    {
        $this->client->request('GET', '/book/user1_author1_title3/delete');
        self::assertResponseRedirects('/');

        $this->client->followRedirect();
        self::assertSelectorNotExists('#user1_author1_title3');
    }
}
