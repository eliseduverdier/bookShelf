<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ColorExtension extends AbstractExtension
{
    /** @codeCoverageIgnore */
    public function getFilters(): array
    {
        return [
            new TwigFilter('lighter', [$this, 'lighterColor']),
            new TwigFilter('darker', [$this, 'darkerColor']),
        ];
    }

    public function lighterColor(string $color, int $percentage = 20): string
    {
        return $this->changeColorLuminosity($color, $percentage);
    }

    public function darkerColor(string $color, int $percentage = 20): string
    {
        return $this->changeColorLuminosity($color, -$percentage);
    }

    protected function changeColorLuminosity(string $color, int $amount): string
    {
        if (preg_match('/#(..)(..)(..)/', $color, $matches)) {
            $R = hexdec($matches[1]);
            $G = hexdec($matches[2]);
            $B = hexdec($matches[3]);

            $newR = dechex(min(255, (int) ($R + ($R * $amount) / 100)));
            $newR = str_pad($newR, 2, '0', STR_PAD_LEFT);

            $newG = dechex(min(255, (int) ($G + ($G * $amount) / 100)));
            $newG = str_pad($newG, 2, '0', STR_PAD_LEFT);

            $newB = dechex(min(255, (int) ($B + ($B * $amount) / 100)));
            $newB = str_pad($newB, 2, '0', STR_PAD_LEFT);

            return "#{$newR}{$newG}{$newB}";
        }

        return $amount > 0 ? '#ffffff' : '#000000';
    }
}
