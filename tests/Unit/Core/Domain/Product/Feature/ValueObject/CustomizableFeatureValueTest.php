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

namespace Tests\Unit\Core\Domain\Product\Feature\ValueObject;

use Generator;
use PHPUnit\Framework\TestCase;
use PrestaShop\PrestaShop\Core\Domain\Product\Feature\Exception\ProductFeatureConstraintException;
use PrestaShop\PrestaShop\Core\Domain\Product\Feature\ValueObject\CustomizableFeatureValue;

class CustomizableFeatureValueTest extends TestCase
{
    public function testItDetectsThatNameIsTooLong(): void
    {
        $this->expectException(ProductFeatureConstraintException::class);
        $this->expectExceptionCode(ProductFeatureConstraintException::CUSTOMIZABLE_FEATURE_VALUE_TOO_LONG);

        $tooLongName = str_repeat('a', CustomizableFeatureValue::MAX_SIZE + 1);

        new CustomizableFeatureValue(1, $tooLongName);
    }

    /**
     * @dataProvider provideInvalidNames
     */
    public function testItDetectsInvalidName(string $invalidName): void
    {
        $this->expectException(ProductFeatureConstraintException::class);
        $this->expectExceptionCode(ProductFeatureConstraintException::INVALID_CUSTOMIZABLE_FEATURE_VALUE);

        new CustomizableFeatureValue(1, $invalidName);
    }

    public function provideInvalidNames(): ?Generator
    {
        yield [
            '<something>',
        ];

        yield [
            '{object}',
        ];

        yield [
            '=hashtag=',
        ];
    }
}