<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;

class AmortizationScheduleController extends Controller
{
    public function show(Loan $loan)
    {
        return view('amortization.show', compact('loan'));
    }
}
