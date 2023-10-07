<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = ['loan_amount', 'annual_interest_rate', 'loan_term'];
    private $memo = [];

    public function borrower()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function amortizationSchedules()
    {
        return $this->hasMany(AmortizationSchedule::class);
    }

    public function extraRepaymentSchedules()
    {
        return $this->hasMany(ExtraRepaymentSchedule::class);
    }

    /**
     * Calculate the monthly payment for the loan and memoize it.
     *
     * @return float
     */
    public function calculateMonthlyPayment()
    {
        return $this->remember('monthly_payment', function () {
            $monthlyInterestRate = ($this->annual_interest_rate / 12) / 100;
            $loanTermMonths = $this->loan_term * 12;
            return round(($this->loan_amount * $monthlyInterestRate) / (1 - pow(1 + $monthlyInterestRate, -$loanTermMonths)), 2);
        });
    }

    /**
     * Create and store an amortization schedule for the loan.
     */
    public function generateAmortizationSchedule()
    {
        $monthlyPayment = $this->calculateMonthlyPayment();
        $loanBalance = $this->loan_amount;
        $monthNumber = 1;

        while ($monthNumber <= $this->loan_term * 12) {
            $scheduleData = $this->calculateMonthlyComponents($loanBalance, $monthlyPayment);
            $this->createAmortizationRecord($monthNumber, $scheduleData);

            $loanBalance = $scheduleData['ending_balance'];
            $monthNumber++;
        }
    }

    /**
     * Calculate monthly principal, interest, and ending balance.
     *
     * @param float $loanBalance
     * @param float $monthlyPayment
     * @return array ['monthlyInterest', 'monthlyPrincipal', 'endingBalance']
     */
    private function calculateMonthlyComponents($loanBalance, $monthlyPayment)
    {
        $monthlyInterestRate = ($this->annual_interest_rate / 12) / 100;
        $monthlyInterest = $loanBalance * $monthlyInterestRate;
        $monthlyPrincipal = $monthlyPayment - $monthlyInterest;
        $endingBalance = $loanBalance - $monthlyPrincipal;

        return compact('monthlyInterest', 'monthlyPrincipal', 'endingBalance');
    }

    /**
     * Create an amortization schedule record.
     *
     * @param int $monthNumber
     * @param array $scheduleData
     */
    private function createAmortizationRecord($monthNumber, array $scheduleData)
    {
        $scheduleData['month_number'] = $monthNumber;
        $amortizationSchedule = new AmortizationSchedule($scheduleData);
        $this->amortizationSchedules()->save($amortizationSchedule);
    }

    /**
     * Apply an extra repayment to the loan.
     *
     * @param float $extraRepaymentAmount
     */
    public function applyExtraRepayment($extraRepaymentAmount)
    {
        $this->validateExtraRepayment($extraRepaymentAmount);

        $this->generateAmortizationSchedule();
        $latestAmortizationSchedule = $this->amortizationSchedules()->latest()->first();
        $newLoanBalance = $latestAmortizationSchedule->ending_balance - $extraRepaymentAmount;

        $extraRepaymentData = $this->calculateExtraRepaymentData($latestAmortizationSchedule, $newLoanBalance, $extraRepaymentAmount);
        $this->createExtraRepaymentRecord($extraRepaymentData);
    }

    /**
     * Calculate data for extra repayment schedule.
     *
     * @param AmortizationSchedule $latestAmortizationSchedule
     * @param float $newLoanBalance
     * @param float $extraRepaymentAmount
     * @return array
     */
    private function calculateExtraRepaymentData(AmortizationSchedule $latestAmortizationSchedule, $newLoanBalance, $extraRepaymentAmount)
    {
        $remainingLoanTerm = $this->calculateRemainingLoanTerm($newLoanBalance);
        return [
            'month_number' => $latestAmortizationSchedule->month_number + 1,
            'starting_balance' => $latestAmortizationSchedule->ending_balance,
            'monthly_payment' => $latestAmortizationSchedule->monthly_payment,
            'principal_component' => $newLoanBalance - $latestAmortizationSchedule->ending_balance,
            'interest_component' => $latestAmortizationSchedule->monthly_payment - ($newLoanBalance - $latestAmortizationSchedule->ending_balance),
            'extra_repayment_made' => $extraRepaymentAmount,
            'ending_balance' => $newLoanBalance,
            'remaining_loan_term' => $remainingLoanTerm,
        ];
    }

    /**
     * Validate the extra repayment amount.
     *
     * @param float $extraRepaymentAmount
     * @throws \InvalidArgumentException
     */
    private function validateExtraRepayment($extraRepaymentAmount)
    {
        if ($extraRepaymentAmount <= 0) {
            throw new \InvalidArgumentException('Extra repayment amount must be a positive number.');
        }
    }

    /**
     * Calculate the remaining loan term.
     *
     * @param float $newLoanBalance
     * @return int
     */
    private function calculateRemainingLoanTerm($newLoanBalance)
    {
        $monthlyInterestRate = ($this->annual_interest_rate / 12) / 100;
        return ceil(-log(1 - ($newLoanBalance / $this->loan_amount)) / log(1 + ($monthlyInterestRate / 12)));
    }

    /**
     * Memoize expensive calculations.
     *
     * @param string $key
     * @param callable $callback
     * @return mixed
     */
    private function remember($key, callable $callback)
    {
        if (!isset($this->memo[$key])) {
            $this->memo[$key] = $callback();
        }

        return $this->memo[$key];
    }
}
