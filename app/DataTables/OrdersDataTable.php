<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
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
            $edit = '<a href="' . route('orders.edit', $query->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
            $show = '<a href="' . route('orders.show', $query->id) . '" class="btn btn-sm btn-info ml-2"><i class="fas fa-eye"></i></a>';
            $delete = '<a href="' . route('orders.destroy', $query->id) . '" class="delete-item btn btn-sm btn-danger ml-2"><i class="fas fa-trash"></i></a>';

            return $edit .$show .$delete ;
        })
        ->addColumn('client_name', function ($order) {
            return $order->client->first_name . ' ' . $order->client->last_name;
        })
        ->addColumn('inventory', function ($order) {
            return $order->inventory->fuel_type;
        })
        ->editColumn('order_date', function ($order) {
            return $order->order_date->format('Y-m-d');
        })
        ->addColumn('total', function ($order) {
            return '$' . number_format($order->total, 2);
        })
        ->editColumn('status', function ($order) {
            $status = ucfirst($order->status);
            $badgeClass = $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger');
            return '<span class="badge badge-' . $badgeClass . '">' . $status . '</span>';
        })
        ->rawColumns(['action', 'status'])
        ->setRowId('id');
}

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        //return $model->newQuery();
        return $model->with(['client', 'inventory'])->newQuery();

    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('orders-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('client_name')->title('Client Name'),
            Column::make('inventory')->title('Fuel Type'),
            Column::make('quantity'),
            Column::make('price')->title('Price per Unit'),
            Column::make('total')->title('Total Price'),
            Column::make('order_date')->title('Order Date'),
            Column::make('status'),
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
        return 'Orders_' . date('YmdHis');
    }
}
