<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AmortizationScheduleController;

// Route for creating a new loan (Display the loan creation form)
Route::get('/loan/create', [LoanController::class, 'create'])->name('loan.create');

// Route for submitting a new loan (Store the loan details in the database)
Route::post('/loan/store', [LoanController::class, 'store'])->name('loan.store');

// Route for displaying loan details (Display the original loan details and apply extra repayments)
Route::get('/loan/show/{loan}', [LoanController::class, 'show'])->name('loan.show');

// Route for applying extra repayments to a loan (Update the loan with extra repayment amount)
Route::post('/loan/apply-extra-repayment/{loan}', [LoanController::class, 'applyExtraRepayment'])->name('loan.applyExtraRepayment');

// New route for showing recalculated loan details after extra repayments (Display recalculated loan and effective interest rate)
Route::get('/loan/show-recalculated/{loan}', [LoanController::class, 'showRecalculatedLoan'])->name('loan.showRecalculatedLoan');

// Route for viewing the amortization schedule (Display the loan's amortization schedule)
Route::get('/loan/amortization/{loan}', [AmortizationScheduleController::class, 'show'])->name('loan.amortization');
