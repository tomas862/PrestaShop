<?php

namespace Tests\Unit\Core\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use PrestaShop\PrestaShop\Core\Domain\Exception\DomainConstraintException;
use PrestaShop\PrestaShop\Core\Domain\ValueObject\Price;

class PriceTest extends TestCase
{
    public function testItDoesNotAllowNegativeValues(): void
    {
        $this->expectException(DomainConstraintException::class);
        $this->expectExceptionCode(DomainConstraintException::INVALID_PRICE);

        $negativeValue = -0.1;

        new Price(
            $negativeValue
        );
    }
}