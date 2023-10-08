@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Recalculated Loan</h1>
        <div>
            <p>Loan Amount: {{ $loan->loan_amount }}</p>
            <p>Annual Interest Rate: {{ $loan->annual_interest_rate }}%</p>
            <p>Original Loan Term: {{ $loan->loan_term }} years</p>
            <p>User ID: {{ $loan->user_id }}</p>
        </div>
        <div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Starting Balance</th>
                        <th>Monthly Payment</th>
                        <th>Principal</th>
                        <th>Interest</th>
                        <th>Extra Repayment Made</th>
                        <th>Ending Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recalculatedLoanSchedule as $entry)
                        <tr>
                            <td>{{ $entry->month }}</td>
                            <td>{{ $entry->starting_balance }}</td>
                            <td>{{ $entry->monthly_payment }}</td>
                            <td>{{ $entry->principal }}</td>
                            <td>{{ $entry->interest }}</td>
                            <td>{{ $entry->extra_repayment_made }}</td>
                            <td>{{ $entry->ending_balance }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
