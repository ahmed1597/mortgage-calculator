@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Effective Interest Rate</h1>
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
                        <th>Remaining Balance</th>
                        <th>Effective Interest Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($effectiveInterestRates as $entry)
                        <tr>
                            <td>{{ $entry->month }}</td>
                            <td>{{ $entry->remaining_balance }}</td>
                            <td>{{ $entry->effective_interest_rate }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
