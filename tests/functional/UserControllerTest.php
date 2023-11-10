<?php

namespace App\Tests\functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
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

    public function testSetSettings(): void
    {
        $this->client->request('GET', '/settings');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('change color', [
            'color' => '#aaaaaa',
        ]);
        self::assertResponseRedirects('/settings');
        $this->client->followRedirect();
        self::assertSelectorTextContains('style', '--fg-color: #aaaaaa;');
    }
}
