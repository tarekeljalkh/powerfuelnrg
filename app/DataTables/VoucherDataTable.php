<?php

namespace App\DataTables;

use App\Models\Journal;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VoucherDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $edit = '<a href="' . route('vouchers.edit', $query->trans_id) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                $show = '<a href="' . route('vouchers.show', $query->trans_id) . '" class="btn btn-sm btn-info ml-2"><i class="fas fa-eye"></i></a>';
                $delete = '<a href="' . route('vouchers.destroy', $query->trans_id) . '" class="delete-item btn btn-sm btn-danger ml-2"><i class="fas fa-trash"></i></a>';

                return $edit . $show . $delete;
            })
            ->rawColumns(['action'])
            ->setRowId('trans_id'); // Use 'trans_id' since that's the primary key in your Journal model
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Journal $model): QueryBuilder
    {
        // Modify the query to include any necessary relationships or filters
        // Fetch journals where the related transactionType has a trans_code of 'JV'
        return $model->newQuery()
            ->with('transactionType') // Ensure the transactionType relationship is loaded
            ->whereHas('transactionType', function ($query) {
                $query->where('trans_code', 'Jv'); // Filter by trans_code 'JV'
            });
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('voucher-table')
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
            Column::make('trans_id')->title('Transaction ID'),
            Column::make('trans_code')->title('Transaction Code'),
            Column::make('manual_ref')->title('Manual Reference'),
            Column::make('trans_date')->title('Transaction Date'),
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
        return 'Voucher_' . date('YmdHis');
    }
}
