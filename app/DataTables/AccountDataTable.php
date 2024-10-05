<?php

namespace App\DataTables;

use App\Models\Account;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AccountDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('is_active', function ($account) {
                return $account->is_active ? 'Yes' : 'No';
            })
            ->addColumn('action', function ($account) {
                $edit = '<a href="' . route('accounts.edit', $account->account_code) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                $show = '<a href="' . route('accounts.show', $account->account_code) . '" class="btn btn-sm btn-info ml-2"><i class="fas fa-eye"></i></a>';
                $delete = '<a href="' . route('accounts.destroy', $account->account_code) . '" class="delete-item btn btn-sm btn-danger ml-2"><i class="fas fa-trash"></i></a>';

                return $edit . $show . $delete;
            })
            ->rawColumns(['action'])
            ->setRowId('account_code'); // Use 'account_code' as the primary key
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Account $model): QueryBuilder
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
            ->setTableId('accounts-table')
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
            Column::make('account_code')->title('Account Code'),
            Column::make('account_name')->title('Account Name'),
            Column::make('account_type')->title('Account Type'),
            Column::make('currency_code')->title('Currency Code'),
            Column::make('is_active')->title('Is Active'),  // No render needed here
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
        return 'Accounts_' . date('YmdHis');
    }
}
