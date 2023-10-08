@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Loan Details</h1>
        <div>
            <p>Loan Amount: {{ $loan->loan_amount }}</p>
            <p>Annual Interest Rate: {{ $loan->annual_interest_rate }}%</p>
            <p>Loan Term: {{ $loan->loan_term }} years</p>
            <p>Borrower: {{ $loan->borrower->name }}</p>
        </div>
        <div>
            <!-- Display the amortization schedule -->
            @include('amortization.show')
        </div>
        <div>
            <form method="POST" action="{{ route('loan.applyExtraRepayment', ['loan' => $loan->id]) }}">
                @csrf
                <div class="form-group">
                    <label for="extra_repayment_amount">Extra Repayment Amount:</label>
                    <input type="text" name="extra_repayment_amount" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Apply Extra Repayment</button>
            </form>
        </div>
    </div>
@endsection
