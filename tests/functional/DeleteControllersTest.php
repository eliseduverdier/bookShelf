<?php

declare(strict_types=1);

namespace App\Tests\functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
class DeleteControllersTest extends WebTestCase
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

    public function testDeleteBook(): void
    {
        $this->client->request('GET', '/book/user1_author1_title3/delete');
        self::assertResponseRedirects('/');

        $this->client->followRedirect();
        self::assertSelectorNotExists('#user1_author1_title3');
    }
    public function testDeleteUnknownBook(): void
    {
        $this->client->request('GET', '/book/nope/delete');
        self::assertResponseIsSuccessful();

        self::assertSelectorTextContains('div.error', 'Error while deleting « nope » : Book does not exists');
    }
}
