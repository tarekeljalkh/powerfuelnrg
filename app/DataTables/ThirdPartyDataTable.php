<?php

namespace App\DataTables;

use App\Models\ThirdParty;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ThirdPartyDataTable extends DataTable
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
                $edit = '<a href="' . route('clients.edit', $query->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                $show = '<a href="' . route('clients.show', $query->id) . '" class="btn btn-sm btn-info ml-2"><i class="fas fa-eye"></i></a>';
                $report = '<a href="' . route('reports.client_specific', ['id' => $query->id]) . '" class="btn btn-sm btn-warning ml-2"><i class="fas fa-chart-bar"></i> Report</a>';
                $delete = '<a href="' . route('clients.destroy', $query->id) . '" class="delete-item btn btn-sm btn-danger ml-2"><i class="fas fa-trash"></i></a>';

                return $edit . $show . $report . $delete;
            })
            ->rawColumns(['action'])
            ->setRowId('id'); // Update to use ThirdId
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ThirdParty $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('thirdparty-table')
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
            Column::make('id'),
            Column::make('name'),
            Column::make('address'),
            Column::make('phone'),
            Column::make('email'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ThirdParty_' . date('YmdHis');
    }
}
