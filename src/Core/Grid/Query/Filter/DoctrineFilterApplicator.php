<?php
/**
 * 2007-2019 PrestaShop SA and Contributors
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

namespace PrestaShop\PrestaShop\Core\Grid\Query\Filter;

use Doctrine\DBAL\Query\QueryBuilder;

final class DoctrineFilterApplicator implements DoctrineFilterApplicatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(QueryBuilder $qb, SqlFilters $filters, array $filterValues)
    {
        if (empty($filterValues)) {
            return;
        }

        foreach ($filters->getFilters() as $filter) {
            $sqlField = $filter['sql_field'];
            $filterName = $filter['filter_name'];

            if (!isset($filterValues[$filterName])) {
                continue;
            }

            $value = $filterValues[$filterName];

            switch ($filter['comparison']) {
                case SqlFilters::WHERE_STRICT:
                    $qb->andWhere("$sqlField = :$filterName");
                    $qb->setParameter($filterName, $value);

                    break;
                case SqlFilters::WHERE_LIKE:
                    $qb->andWhere("$sqlField LIKE :$filterName");
                    $qb->setParameter($filterName, '%' . $value . '%');

                    break;
                case SqlFilters::HAVING_LIKE:
                    $qb->andHaving("$sqlField LIKE :$filterName");
                    $qb->setParameter($filterName, '%' . $value . '%');

                    break;
                case SqlFilters::WHERE_DATE:
                    if (isset($value['from'])) {
                        $name = sprintf('%s_from', $filterName);

                        $qb->andWhere("$sqlField >= :$name");
                        $qb->setParameter($name, sprintf('%s %s', $value['from'], '0:0:0'));
                    }

                    if (isset($value['to'])) {
                        $name = sprintf('%s_to', $filterName);

                        $qb->andWhere("$sqlField <= :$name");
                        $qb->setParameter($name, sprintf('%s %s', $value['to'], '23:59:59'));
                    }

                    break;
                case SqlFilters::MIN_MAX:
                    $minFieldExists = isset($value['min_field']);
                    $maxFieldExists = isset($value['max_field']);

                    if (!$minFieldExists && !$maxFieldExists) {
                        break;
                    }

                    $bothFieldsExist = $minFieldExists && $maxFieldExists;
                    $minFieldExistsOnly = $minFieldExists && !$maxFieldExists;
                    $maxFieldExistsOnly = $maxFieldExists && !$minFieldExists;
                    $bothFieldsAreEqual = $bothFieldsExist && $value['min_field'] === $value['max_field'];

                    $minFieldSqlCondition = "$sqlField >= :{$filterName}_min";
                    $maxFieldSqlCondition = "$sqlField <= :{$filterName}_max";

                    if ($bothFieldsExist && !$bothFieldsAreEqual) {
                        $qb->andWhere("$minFieldSqlCondition AND $maxFieldSqlCondition");
                        $qb->setParameter("{$filterName}_min", $value['min_field']);
                        $qb->setParameter("{$filterName}_max", $value['max_field']);
                    } elseif ($minFieldExistsOnly) {
                        $qb->andWhere($minFieldSqlCondition);
                        $qb->setParameter("{$filterName}_min", $value['min_field']);
                    } elseif ($maxFieldExistsOnly) {
                        $qb->andWhere($maxFieldSqlCondition);
                        $qb->setParameter("{$filterName}_max", $value['max_field']);
                    } elseif ($bothFieldsAreEqual) {
                        $qb->andWhere("$sqlField = :$filterName");
                        $qb->setParameter($filterName, $value['min_field']);
                    }

                    break;
            }
        }
    }
}
