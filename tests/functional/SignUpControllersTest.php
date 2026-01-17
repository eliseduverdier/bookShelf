<?php

declare(strict_types=1);

namespace App\Tests\functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class SignUpControllersTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->find(1);
        $this->client->loginUser($testUser);
    }

    public function testSignUp(): void
    {
        $this->client->request('GET', '/signup');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign up', [
            '_username' => 'user3',
            '_password' => 'abc',
        ]);
        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertSelectorTextContains('.user-open-menu', 'Hello user3 !');
    }
}
