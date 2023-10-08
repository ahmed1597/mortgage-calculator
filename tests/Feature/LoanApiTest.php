<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Loan;

class LoanApiTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateLoanSuccessfully()
    {
        $loanData = [
            'loan_amount' => 100000,
            'interest_rate' => 5,
            'loan_term' => 30,
            'starting_balance' => 100000, 
            'monthly_payment' => 500,
            'user_id' => 1, 
        ];

        $response = $this->postJson('/api/loans', $loanData);

        $response->assertStatus(201);
    }
}
