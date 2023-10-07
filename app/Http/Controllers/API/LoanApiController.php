<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\User;

class LoanApiController extends Controller
{
    public function index()
    {
        $loans = Loan::all();
        return response()->json($loans);
    }

    public function store(Request $request)
    {
        $this->validateLoanRequest($request);

        $user = User::findOrFail($request->input('user_id'));
        $loan = $this->createLoan($request->only(['loan_amount', 'annual_interest_rate', 'loan_term']), $user);
        $this->generateAmortizationSchedule($loan);

        return response()->json($loan, 201);
    }

    public function show(Loan $loan)
    {
        return response()->json($loan);
    }

    public function applyExtraRepayment(Request $request, Loan $loan)
    {
        $this->validateExtraRepaymentRequest($request);

        $extraRepaymentAmount = $request->input('extra_repayment_amount');
        $this->applyExtraRepaymentToLoan($loan, $extraRepaymentAmount);

        return response()->json(['message' => 'Extra repayment applied successfully']);
    }

    protected function validateLoanRequest(Request $request)
    {
        $request->validate([
            'loan_amount' => 'required|numeric|min:1',
            'annual_interest_rate' => 'required|numeric|min:0',
            'loan_term' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
        ]);
    }

    protected function createLoan(array $data, User $user)
    {
        return $user->loans()->create($data);
    }

    protected function generateAmortizationSchedule(Loan $loan)
    {
        $loan->generateAmortizationSchedule();
    }

    protected function validateExtraRepaymentRequest(Request $request)
    {
        $request->validate([
            'extra_repayment_amount' => 'required|numeric|min:0',
        ]);
    }

    protected function applyExtraRepaymentToLoan(Loan $loan, $extraRepaymentAmount)
    {
        $loan->applyExtraRepayment($extraRepaymentAmount);
    }
}
