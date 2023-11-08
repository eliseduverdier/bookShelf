<?php

namespace App\Tests\Twig;

use App\Twig\ColorExtension;
use PHPUnit\Framework\TestCase;

class ColorExtensionTest extends TestCase
{
    protected ColorExtension $colorExtension;

    protected function setUp(): void
    {
        $this->colorExtension = new ColorExtension();
    }

    public function testLighterColor(): void
    {
        self::assertEquals('#ffffff', $this->colorExtension->lighterColor('#FFFFFF'));
        self::assertEquals('#cccccc', $this->colorExtension->lighterColor('#aaaaaa'));

        self::assertEquals('#ff0066', $this->colorExtension->lighterColor('#FF0055'));
        self::assertEquals('#666666', $this->colorExtension->lighterColor('#555555'));
        self::assertEquals('#555555', $this->colorExtension->lighterColor('#555555', 0));
        self::assertEquals('#5d5d5d', $this->colorExtension->lighterColor('#555555', 10));
        self::assertEquals('#7f7f7f', $this->colorExtension->lighterColor('#555555', 50));

        self::assertEquals('#ffffff', $this->colorExtension->lighterColor('nope'));
    }

    public function tesDarkerColor(): void
    {
        self::assertEquals('#aaaaaa', $this->colorExtension->lighterColor('#cccccc'));

        self::assertEquals('#000000', $this->colorExtension->lighterColor('nope'));
    }

}
