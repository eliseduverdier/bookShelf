<?php

namespace App\Tests\functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class ViewControllersTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testViewBook(): void
    {
        $this->client->request('GET', '/book/user1_author1_title1');
        self::assertSelectorTextContains('h1', 'My Title 1');
    }

    public function testViewUnknownBook(): void
    {
        $this->client->request('GET', '/book/unknown');
        self::assertSelectorTextContains('div.error p', 'No book found with slug « unknown »');

//        $this->client->request('GET', '/book/unknown/edit');
//        dd($this->client->getResponse()->getContent());
//        self::assertResponseRedirects('/login');
    }
}
