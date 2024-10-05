<?php

namespace App\DataTables;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CurrencyDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($currency) {
                $edit = '<a href="' . route('currencies.edit', $currency->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                $show = '<a href="' . route('currencies.show', $currency->id) . '" class="btn btn-sm btn-info ml-2"><i class="fas fa-eye"></i></a>';
                $delete = '<a href="' . route('currencies.destroy', $currency->id) . '" class="delete-item btn btn-sm btn-danger ml-2"><i class="fas fa-trash"></i></a>';

                return $edit . $show . $delete;
            })
            ->rawColumns(['action'])
            ->setRowId('id'); // Use 'id' as the primary key
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Currency $model): QueryBuilder
    {
        // Modify the query to include any necessary filters
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('currencies-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('lBfrtip')
            ->scrollX(true)  // Enable horizontal scrolling
            ->autoWidth(false) // Disable automatic width calculation to use full width
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('colvis')  // Column visibility button
            ])
            ->responsive(true);  // Enable responsive behavior
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('currency_code')->title('Currency Code'),
            Column::make('currency_name')->title('Currency Name'),
            Column::make('exchange_rate')->title('Exchange Rate'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Currencies_' . date('YmdHis');
    }
}
