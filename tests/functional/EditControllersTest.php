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

    public function testViewAndEditBookWrongData(): void
    {
        $this->client->request('GET', '/book/user1_author1_title1/edit');
        $this->client->submitForm('OK', ['title' => null]);

        self::assertSelectorTextContains('div.error', 'Error while editing « user1_author1_title1 » : Title and author must be filled');
    }

    public function testViewUnknownBook(): void
    {
        $this->client->request('GET', '/book/unknown');
        self::assertSelectorTextContains('div.error', 'No book found with slug « unknown »');

        $this->client->request('GET', '/book/unknown/edit');
        self::assertSelectorTextContains('div.error', 'No book found with slug « unknown »');
    }

    public function testEditOtherPeoplesBook(): void
    {
        $this->client->request('POST', '/book/user2_author2_title2/edit');
        self::assertSelectorTextContains('div.error p', 'Error while editing « user2_author2_title2 » : Not your book');
    }
}
