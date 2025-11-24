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
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                $edit = '<a href="' . route('vouchers.edit', $row->trans_id) . '" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                         </a>';

                $show = '<a href="' . route('vouchers.show', $row->trans_id) . '" class="btn btn-sm btn-info ml-2">
                            <i class="fas fa-eye"></i>
                         </a>';

                $delete = '<a href="' . route('vouchers.destroy', $row->trans_id) . '" class="delete-item btn btn-sm btn-danger ml-2">
                            <i class="fas fa-trash"></i>
                          </a>';

                return $edit . $show . $delete;
            })
            ->rawColumns(['action'])
            ->setRowId('trans_id');
    }

    /**
     * Query for the DataTable.
     */
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
                j.trans_code,
                j.manual_ref,
                jli.reference,
                jli.description,
                jli.amount,
                jli.currency,
                jli.third_party_id,
                j.trans_date,
                 DATE_FORMAT(j.trans_date, "%d/%m/%Y") AS formated_trans_date
            ')
            ->where('j.trans_code', 'Jv')
            ->whereDate('j.trans_date', '>=', $start_date)
            ->whereDate('j.trans_date', '<=', $end_date)
            ->orderBy('j.trans_date', 'asc');

        // From date
        if ($request->filled('start_date')) {
            $query->whereDate('j.trans_date', '>=', $start_date);
        }

        // To date
        if ($request->filled('end_date')) {
            $query->whereDate('j.trans_date', '<=', $end_date);
        }

        // Client (third_party)
        if ($request->filled('third_party_id')) {
            $query->where('jli.third_party_id', $request->get('third_party_id'));
        }

        // Manual reference
        if ($request->filled('manual_ref')) {
            $query->where('j.manual_ref', 'like', '%' . $request->get('manual_ref') . '%');
        }

        // VAT reference
        if ($request->filled('reference')) {
            $query->where('jli.reference', 'like', '%' . $request->get('reference') . '%');
        }

        // Description
        if ($request->filled('description')) {
            $query->where('jli.description', 'like', '%' . $request->get('description') . '%');
        }

        return $query;
    }

    /**
     * HTML builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('voucher-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('lBfrtip')
            ->scrollX(true)
            ->autoWidth(false)
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('colvis'),
            ])
            ->parameters([
                'pageLength' => 50,
                'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            ])
            ->responsive(true);
    }

    /**
     * Columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('formated_trans_date')->title('Voucher Date'),
            Column::make('trans_code')->title('Trans Code'),
            Column::make('client_name')->title('Client'),
            Column::make('manual_ref')->title('Reference'),
            Column::make('reference')->title('VAT'),
            Column::make('description')->title('Description'),
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
     * Export filename (used by buttons).
     */
    protected function filename(): string
    {
        return 'Voucher_' . date('YmdHis');
    }
}
