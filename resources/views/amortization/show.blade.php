<table class="table">
    <thead>
        <tr>
            <th>Month</th>
            <th>Starting Balance</th>
            <th>Monthly Payment</th>
            <th>Principal</th>
            <th>Interest</th>
            <th>Ending Balance</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loan->amortizationSchedules as $entry)
            <tr>
                <td>{{ $entry->month }}</td>
                <td>{{ $entry->starting_balance }}</td>
                <td>{{ $entry->monthly_payment }}</td>
                <td>{{ $entry->principal }}</td>
                <td>{{ $entry->interest }}</td>
                <td>{{ $entry->ending_balance }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
