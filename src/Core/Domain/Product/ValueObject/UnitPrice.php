<?php

namespace PrestaShop\PrestaShop\Core\Domain\Product\ValueObject;

use PrestaShop\Decimal\Number;
use PrestaShop\PrestaShop\Core\Domain\Exception\DomainConstraintException;
use PrestaShop\PrestaShop\Core\Domain\Product\Exception\ProductConstraintException;
use PrestaShop\PrestaShop\Core\Domain\ValueObject\Price;

/**
 * Price per unit - e.g 10 per kilo.
 */
final class UnitPrice
{
    /**
     * @var Number
     */
    private $price;

    /**
     * @var string
     */
    private $unit;

    /**
     * @param float $price
     * @param string $unit
     *
     * @throws ProductConstraintException
     */
    public function __construct(float $price, string $unit)
    {
        try {
            $this->price = (new Price($price))->getValue();
        } catch (DomainConstraintException $e) {
            throw new ProductConstraintException(
                'Invalid products unit price',
                ProductConstraintException::INVALID_UNIT_PRICE,
                $e
            );
        }

        $this->unit = $unit;
    }

    /**
     * @return Number
     */
    public function getPrice(): Number
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }
}