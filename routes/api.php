<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoanApiController;

// API route for listing all loans (Get a list of all loans)
Route::get('/api/loans', [LoanApiController::class, 'index']);

// API route for creating a new loan (Store the loan details in the database)
Route::post('/api/loans', [LoanApiController::class, 'store']);

// API route for displaying loan details (Get the original loan details)
Route::get('/api/loans/{loan}', [LoanApiController::class, 'show'])->name('api.loans.show');

// API route for applying extra repayments to a loan (Update the loan with extra repayment amount)
Route::post('/api/loans/{loan}/apply-extra-repayment', [LoanApiController::class, 'applyExtraRepayment']);

// New API route for showing recalculated loan details after extra repayments (Display recalculated loan and effective interest rate)
Route::get('/api/loans/{loan}/show-recalculated', [LoanApiController::class, 'showRecalculatedLoan']);
