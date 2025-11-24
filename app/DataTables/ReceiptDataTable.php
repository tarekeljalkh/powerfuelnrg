<?php

namespace App\DataTables;

use App\Models\Journal;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReceiptDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $edit = '<a href="' . route('receipts.edit', $query->trans_id) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                $show = '<a href="' . route('receipts.show', $query->trans_id) . '" class="btn btn-sm btn-info ml-2"><i class="fas fa-eye"></i></a>';
                $delete = '<a href="' . route('receipts.destroy', $query->trans_id) . '" class="delete-item btn btn-sm btn-danger ml-2"><i class="fas fa-trash"></i></a>';
                return $edit . $show . $delete;
            })
            ->rawColumns(['action'])
            ->setRowId('trans_id');
    }

    /**
     * Get the query source of dataTable.
     */
    // public function query(Journal $model): QueryBuilder
    // {
    //     // Filter journals where the transaction type is "receipt"
    //     // Fetch journals where the related transactionType has a trans_code of 'JV'
    //     return $model->newQuery()
    //         ->with('transactionType') // Ensure the transactionType relationship is loaded
    //         ->whereHas('transactionType', function ($query) {
    //             $query->where('trans_code', 'Rv'); // Filter by trans_code 'JV'
    //         })
    //         ->whereDate('trans_date', '>=', '2025-01-01') // filter from 1/1/2025
    //         ->orderBy('trans_date', 'desc'); // latest first
    //     ;
    // }

    public function query(Journal $model): QueryBuilder
{
    $request = request();

     $start_date = $request->input('start_date', now()->startOfYear()->format('Y-m-d'));
        $end_date   = $request->input('end_date', now()->format('Y-m-d'));

    $query = $model->newQuery()
        ->from('journals as j')
        ->join('journal_line_items as jli', 'j.trans_id', '=', 'jli.trans_id')
        ->join('third_parties as tp', 'jli.third_party_id', '=', 'tp.id') 
        ->selectRaw('
            tp.name as client_name,
            jli.trans_id,
             trans_code,
            j.manual_ref,
            jli.reference,
            jli.description,
            jli.amount,
            jli.currency,
            jli.third_party_id,
            j.trans_date,
            DATE_FORMAT(j.trans_date, "%d/%m/%Y") AS formated_trans_date
        ')
        ->where('trans_code', 'Rv')
        ->whereDate('j.trans_date', '>=', $start_date)
        ->whereDate('j.trans_date', '<=', $end_date)
        ->orderBy('j.trans_date', 'asc');

   if ($request->filled('start_date')) {
        $query->whereDate('j.trans_date', '>=', $start_date);
    }

    // To date
    if ($request->filled('end_date')) {
        $query->whereDate('j.trans_date', '<=', $end_date);
    }

     // Client name (third_parties.name)
     if ($request->filled('third_party_id')) {
        //$query->where('tp.name', 'like', '%' . $request->get('client_name') . '%');
         $query->where('jli.third_party_id', $request->get('third_party_id'));
     }

    // Manual reference (journals.manual_ref)
    if ($request->filled('manual_ref')) {
        $query->where('j.manual_ref', 'like', '%' . $request->get('manual_ref') . '%');
    }

    // Description (journal_line_items.description)
    if ($request->filled('reference')) {
            $query->where('jli.reference', 'like', '%' . $request->get('reference') . '%');
        }

    return $query;
}

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('receipt-table')
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
            ->parameters([
            'pageLength' => 50, // default rows per page
            'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        ])
            ->responsive(true);  // Enable responsive behavior
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('formated_trans_date')->title('Payment Date'), 
            Column::make('trans_code')->title('Trans Code'),
            Column::make('client_name')->title('Client'),
            Column::make('manual_ref')->title('Payment Method'),
            Column::make('reference')->title('Reference'),
            Column::make('currency')->title('Currency'),
            Column::make('amount')->title('Amount'),
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
        return 'Receipt_' . date('YmdHis');
    }
}
