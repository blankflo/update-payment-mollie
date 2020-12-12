<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use  Hash;
use App\Models\User;
use Laravel\Cashier\FirstPayment\Actions\AddBalance;
use Laravel\Cashier\FirstPayment\Actions\AddGenericOrderItem;
use Laravel\Cashier\FirstPayment\FirstPaymentBuilder;
use Money\Money;



class UpdateMolliePayment extends Controller{

    

    public function handle()
    {
        $billable = Auth::user();
       
        $payment = (new FirstPaymentBuilder($billable))
            ->setRedirectUrl('/billing')
            ->inOrderTo($this->getAddToBalanceActions($billable))
        
            ->create();

         $payment->update();

        return redirect($payment->getCheckoutUrl());
    }

    protected function getAddToBalanceActions($billable)
    {
        return [
            new AddBalance(
                $billable,
                mollie_array_to_money(config('cashier.first_payment.amount')),
                __("Payment method updated")
            )
        ];
}
}