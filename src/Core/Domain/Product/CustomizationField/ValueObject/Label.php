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

namespace PrestaShop\PrestaShop\Core\Domain\Product\CustomizationField\ValueObject;

use PrestaShop\PrestaShop\Core\Domain\Product\CustomizationField\Exception\ProductCustomizationFieldConstraintException;
use function strlen;

/**
 * Customization field label value.
 */
final class Label
{
    public const MAX_SIZE = 255;

    /**
     * @var string
     */
    private $label;

    /**
     * @param string $label
     *
     * @throws ProductCustomizationFieldConstraintException
     */
    public function __construct(string $label)
    {
        if (strlen($label) > self::MAX_SIZE) {
            throw new ProductCustomizationFieldConstraintException(
                sprintf(
                    'Customization field label "%s" has breached max available length of %d',
                    $label,
                    self::MAX_SIZE
                ),
                ProductCustomizationFieldConstraintException::CUSTOMIZATION_FIELD_LABEL_TOO_LONG
            );
        }

        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->label;
    }
}