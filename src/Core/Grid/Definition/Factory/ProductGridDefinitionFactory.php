<?php

namespace PrestaShop\PrestaShop\Core\Grid\Definition\Factory;

use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ImageColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ToggleColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShop\PrestaShop\Core\Hook\HookDispatcherInterface;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use PrestaShopBundle\Form\Admin\Type\YesAndNoChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Defines products grid name, its columns, actions, bulk actions and filters.
 */
final class ProductGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    /**
     * @var bool
     */
    private $isStockManagementEnabled;

    /**
     * @param HookDispatcherInterface $hookDispatcher
     * @param bool $isStockManagementEnabled
     */
    public function __construct(
        HookDispatcherInterface $hookDispatcher,
        $isStockManagementEnabled
    ) {
        parent::__construct($hookDispatcher);
        $this->isStockManagementEnabled = $isStockManagementEnabled;
    }

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return $this->trans('Products', [], 'Admin.Navigation.Menu');
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        $columns = (new ColumnCollection())
            ->add(
                (new BulkActionColumn('bulk'))
                    ->setOptions([
                        'bulk_field' => 'id_product',
                    ])
            )
            ->add(
                (new DataColumn('id_product'))
                    ->setName($this->trans('ID', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'id_product',
                    ])
            )
            ->add(
                (new ImageColumn('image'))
                    ->setName($this->trans('Image', [], 'Admin.Global'))
                    ->setOptions([
                        'src_field' => 'image',
                    ])
            )
            ->add(
                (new DataColumn('name'))
                    ->setName($this->trans('Name', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'name',
                    ])
            )
            ->add(
                (new DataColumn('reference'))
                    ->setName($this->trans('Reference', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'reference',
                    ])
            )
            ->add(
                (new DataColumn('category'))
                    ->setName($this->trans('Category', [], 'Admin.Catalog.Feature'))
                    ->setOptions([
                        'field' => 'category',
                    ])
            )
            ->add(
                (new DataColumn('price_tax_excluded'))
                    ->setName($this->trans('Price (tax excl.)', [], 'Admin.Catalog.Feature'))
                    ->setOptions([
                        'field' => 'price_tax_excluded',
                    ])
            )
            ->add(
                (new DataColumn('price_tax_included'))
                    ->setName($this->trans('Price (tax incl.)', [], 'Admin.Catalog.Feature'))
                    ->setOptions([
                        'field' => 'price_tax_included',
                    ])
            )
            ->add(
                (new ToggleColumn('active'))
                    ->setName($this->trans('Status', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'active',
                        'primary_field' => 'id_product',
                        'route' => 'admin_products_toggle_status',
                        'route_param_name' => 'productId',
                    ])
            )
            ->add((new ActionColumn('actions'))
                ->setName($this->trans('Actions', [], 'Admin.Global'))
                ->setOptions([
                    'actions' => (new RowActionCollection())
                        ->add((new LinkRowAction('edit'))
                            ->setName($this->trans('Edit', [], 'Admin.Actions'))
                            ->setIcon('edit')
                            ->setOptions([
                                'route' => 'admin_products_edit',
                                'route_param_name' => 'productId',
                                'route_param_field' => 'id_product',
                            ])
                        )
                        ->add((new LinkRowAction('preview'))
                            ->setName($this->trans('Preview', [], 'Admin.Actions'))
                            ->setIcon('remove_red_eye')
                            ->setOptions([
                                'route' => 'admin_products_preview',
                                'route_param_name' => 'productId',
                                'route_param_field' => 'id_product',
                            ])
                        )
                        ->add((new SubmitRowAction('duplicate'))
                            ->setName($this->trans('Duplicate', [], 'Admin.Actions'))
                            ->setIcon('content_copy')
                            ->setOptions([
                                'route' => 'admin_products_duplicate',
                                'route_param_name' => 'productId',
                                'route_param_field' => 'id_product',
                            ])
                        )
                        ->add((new SubmitRowAction('delete'))
                            ->setName($this->trans('Delete', [], 'Admin.Actions'))
                            ->setIcon('delete')
                            ->setOptions([
                                'method' => 'DELETE',
                                'route' => 'admin_products_delete',
                                'route_param_name' => 'productId',
                                'route_param_field' => 'id_product',
                                'confirm_message' => $this->trans(
                                    'Delete selected item?',
                                    [],
                                    'Admin.Notifications.Warning'
                                ),
                            ])
                        ),
                ])
            )
        ;

        //todo: test on or off
        if ($this->isStockManagementEnabled) {
            $columns->addAfter(
                'price_tax_included',
                (new DataColumn('quantity'))
                    ->setName($this->trans('Quantity', [], 'Admin.Catalog.Feature'))
                    ->setOptions([
                        'field' => 'quantity',
                    ])
            );
        }

        return $columns;

//        todo: position when category filter is used
        // @see https://github.com/sarjon/PrestaShop/blob/42a80d5931b50e641b8030b82845cec1a3bb5118/src/PrestaShopBundle/Resources/views/Admin/Product/CatalogPage/Lists/products_table.html.twig#L72
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilters()
    {
        return (new FilterCollection())
            ->add(
                (new Filter('name', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Search name', [], 'Admin.Actions'),
                        ],
                    ])
                    ->setAssociatedColumn('name')
            )
            ->add(
                (new Filter('reference', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Search ref.', [], 'Admin.Catalog.Help'),
                        ],
                    ])
                    ->setAssociatedColumn('reference')
            )
            ->add(
                (new Filter('category', TextType::class))
                    ->setTypeOptions([
                        'required' => false,
                        'attr' => [
                            'placeholder' => $this->trans('Search category', [], 'Admin.Catalog.Help'),
                        ],
                    ])
                    ->setAssociatedColumn('category')
            )
            ->add((new Filter('active', YesAndNoChoiceType::class))
                ->setAssociatedColumn('active')
            )
            ->add(
                (new Filter('actions', SearchAndResetType::class))
                    ->setTypeOptions([
                        'reset_route' => 'admin_common_reset_search',
                        'reset_route_params' => [
                            'controller' => 'product',
                            'action' => 'index',
                        ],
                        'redirect_route' => 'admin_products_index',
                    ])
                    ->setAssociatedColumn('actions')
            )
        ;
    }
}
