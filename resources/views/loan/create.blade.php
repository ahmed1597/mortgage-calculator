@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Loan</h1>
        <form method="POST" action="{{ route('loan.store') }}">
            @csrf
            <div class="form-group">
                <label for="loan_amount">Loan Amount:</label>
                <input type="text" name="loan_amount" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="annual_interest_rate">Annual Interest Rate (%):</label>
                <input type="text" name="annual_interest_rate" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="loan_term">Loan Term (in years):</label>
                <input type="text" name="loan_term" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="user_id">Borrower ID:</label>
                <input type="text" name="user_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Loan</button>
        </form>
    </div>
@endsection
