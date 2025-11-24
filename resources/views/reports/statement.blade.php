<!DOCTYPE html>
<html>
<head>
    <title>Statement of Account</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #555; padding: 6px; }
        th { background: #eee; }
        .header-table td { border: none; padding: 2px 0; }
        .bold { font-weight: bold; }
        .right { text-align: right; }
        .center { text-align: center; }
    </style>
</head>
<body>
@if (!request()->routeIs('reports.statement.pdf'))
    <div style="margin-bottom: 15px;">
        <a href="{{ route('reports.statement.pdf', [
                'client_id' => $client->id,
            ]) . '?start_date=' . $start_date . '&end_date=' . $end_date }}"
           class="btn btn-primary"
           style="padding:8px 15px; background:#037bfc; color:white; text-decoration:none;">
            Download PDF
        </a>
    </div>
@endif
<h2 class="center">Statement Of Account</h2>

<table class="header-table">
    <tr>
        <td><strong>From:</strong> {{ date('d/m/Y', strtotime($start_date)) }}</td>
        <td><strong>Till:</strong> {{ date('d/m/Y', strtotime($end_date)) }}</td>
        <td><strong>Third ID:</strong> {{ $client->id }}</td>
    </tr>

    <tr>
        <td><strong>Account:</strong> 41110 CLIENTS</td>
        <td colspan="2"><strong>Third Party:</strong> {{ $client->name }}</td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Trx</th>
             
            <th>Reference</th>
            <th>Description</th>
            <th class="right">Debit</th>
            <th class="right">Credit</th>
            <th class="right">Balance</th>
        </tr>
    </thead>

    <tbody>
    @foreach($rows as $row)
        <tr>
            <td>{{ date('d/m/Y', strtotime($row->trans_date)) }}</td>
            <td>{{ $row->trans_code }}</td>
            
            <td>{{ $row->manual_ref }}</td>
            <td>{{ $row->description }}</td>
            <td class="right">{{ number_format($row->debit, 3) }}</td>
            <td class="right">{{ number_format($row->credit, 3) }}</td>
            <td class="right bold">{{ number_format($row->running_balance, 3) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<br>

<div class="right bold">
    Total Debit: {{ number_format($total_debit, 3) }}<br>
    Total Credit: {{ number_format($total_credit, 3) }}<br>
    Ending Balance: {{ number_format($balance, 3) }}
</div>

<br>
<div>
    <strong>Only:</strong> {{ $amount_words }}
</div>

</body>
</html>
