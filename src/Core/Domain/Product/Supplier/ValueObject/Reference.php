<?php
/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\PrestaShop\Core\Domain\Product\Supplier\ValueObject;

use PrestaShop\PrestaShop\Core\Domain\Product\Supplier\Exception\ProductSupplierConstraintException;

/**
 * Products supplier reference
 */
final class Reference
{
    /**
     * @var string
     */
    private $reference;

    /**
     * @param string $reference
     *
     * @throws ProductSupplierConstraintException
     */
    public function __construct(string $reference)
    {
        $this->assertIsValidReference($reference);

        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     *
     * @throws ProductSupplierConstraintException
     */
    private function assertIsValidReference(string $reference): void
    {
        $pattern = '/^[^<>;={}]*$/u';
        if (!preg_match($pattern, $reference)) {
            throw new ProductSupplierConstraintException(
                sprintf(
                    'Product reference "%s" did not matched pattern "%s"',
                    $reference,
                    $pattern
                ),
                ProductSupplierConstraintException::INVALID_SUPPLIER_REFERENCE
            );
        }
    }
}