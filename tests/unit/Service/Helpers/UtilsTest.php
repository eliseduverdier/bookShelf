<?php

declare(strict_types=1);

namespace App\Tests\unit\Service\Helpers;

use App\Service\Helpers\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    public function testSlugify(): void
    {
        self::assertEquals('eaea', Utils::slugify('éàÉÀ'));
        self::assertEquals('un-deux-trois-4-5', Utils::slugify('UN & deux trois 4 5'));
        self::assertEquals('abc-parentheses-et-apos-trophes', Utils::slugify('abc:?! ({[parentheses]})et\'"«apos’trophes»@'));
        self::assertEquals('unknown', Utils::slugify('@^*$unknown&#§'));
        self::assertEquals('', Utils::slugify('🙂'));
    }
}
