<?php

namespace Novanta\BulkPriceUpdater\Grid\Definition\Factory;

use Novanta\BulkPriceUpdater\Grid\Action\Row\RevertRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\DateTimeColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\LinkColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Status\SeverityLevelColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShop\PrestaShop\Core\Hook\HookDispatcherInterface;
use PrestaShopBundle\Form\Admin\Type\DateRangeType;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class PriceImportLogDefinitionFactory extends AbstractGridDefinitionFactory
{

    const GRID_ID = 'priceimportlog';

    protected function getId()
    {
        return self::GRID_ID;
    }

    protected function getName()
    {
        return $this->trans('Import Log', [], 'Modules.Bulkpriceupdater.Admin');
    }

    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new DataColumn('id_price_import'))
                    ->setName($this->trans('ID', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'id_price_import'
                    ])
            )
            ->add((new LinkColumn('file'))
                    ->setName($this->trans('File', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'file',
                        'route' => 'admin_bulkpriceupdater_import_download',
                        'route_param_name' => 'id',
                        'route_param_field' => 'id_price_import',
                    ])
            )
            ->add((new SeverityLevelColumn('status'))
                    ->setName($this->trans('Status', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'status'
                    ])
            )
            ->add((new DateTimeColumn('date_add'))
                    ->setName($this->trans('Date', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'date_add'
                    ])
            )
            ->add((new ActionColumn('actions'))
                    ->setName($this->trans('Actions', [], 'Admin.Global'))
                    ->setOptions([
                        'actions' => $this->getRowActions()
                    ])
            );
    }

    protected function getFilters()
    {
        return (new FilterCollection())
            ->add(
                (new Filter('id_price_import', IntegerType::class))
                ->setTypeOptions([
                    'required' => false,
                ])
                ->setAssociatedColumn('id_price_import')
            )
            ->add(
                (new Filter('file', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                ])
                ->setAssociatedColumn('file')
            )
            ->add(
                (new Filter('status', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                ])
                ->setAssociatedColumn('status')
            )
            ->add(
                (new Filter('date_add', DateRangeType::class))
                ->setTypeOptions([
                    'required' => false,
                ])
                ->setAssociatedColumn('date_add')
            )
            ->add(
                (new Filter('actions', SearchAndResetType::class))
                ->setTypeOptions([
                    'reset_route' => 'admin_common_reset_search_by_filter_id',
                    'reset_route_params' => [
                        'filterId' => self::GRID_ID,
                    ],
                    'redirect_route' => 'admin_bulkpriceupdater_import_index',
                ])
                ->setAssociatedColumn('actions')
            );
    }

    private function getRowActions()
    {
        return (new RowActionCollection())
            ->add(
                (new RevertRowAction('revert'))
                    ->setName($this->trans('Revert', [], 'Admin.Actions'))
                    ->setIcon('upload')
                    ->setOptions([
                        'route' => 'admin_bulkpriceupdater_import_revert',
                        'route_param_name' => 'id',
                        'route_param_field' => 'id_price_import',
                        'confirm_message' => $this->trans('Do you want to revert to this import?', [], 'Modules.Bulkpriceupdater.Notification'),
                    ])
            );
    }
}
