<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;

class AuthorizeNetController extends Controller
{

    public function planPayWithAuthorizeNet(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';


        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan      = Plan::find($planID);
        $authuser  = \Auth::user();
        $coupon_id = '';
        if($plan)
        {

            $price = $plan->price;
            if(isset($request->coupon) && !empty($request->coupon))
            {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if(!empty($coupons))
                {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;

                    if($usedCoupun >= $coupons->limit)
                    {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price     = $price - $discount_value;
                    $coupon_id = $coupons->id;
                }
                else
                {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            if($price <= 0)
            {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

                if($assignPlan['is_success'] == true && !empty($plan))
                {

                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    Order::create(
                        [
                            'order_id' => $orderID,
                            'name' => null,
                            'email' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price == null ? 0 : $price,
                            'price_currency' => $currency,
                            'txn_id' => '',
                            'payment_type' => 'AuthorizNet',
                            'payment_status' => 'success',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $res['msg']  = __("Plan successfully upgraded.");
                    $res['flag'] = 2;

                    return $res;
                }
                else
                {
                    return Utility::error_res(__('Plan fail to upgrade.'));
                }
            }
            $data = [
                'id' =>  $plan->id,
                'amount' =>  $price,
                'coupon_code' =>  $request->coupon_code,
            ];
            $data  =    json_encode($data);
            try {
                return view('plan.request', compact('plan', 'price','data'));
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }
        }
        else
        {
            return Utility::error_res(__('Plan is deleted.'));
        }
    }

    public function planPayWithAuthorizeNetData(Request $request)
    {
        $input          = $request->all();
        $data           = json_decode($input['data'] , true);
        $adminPaymentSettings = Utility::getAdminPaymentSetting();
        $currency = $adminPaymentSettings['currency'];

        $plan           = Plan::find($data['id']);
        $amount         =  $data['amount'];

        $user = \Auth::user();

        Utility::referralTransaction($plan);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $order = new Order();
        $order->order_id = $orderID;
        $order->name = $user->name;
        $order->card_number = '';
        $order->card_exp_month = '';
        $order->card_exp_year = '';
        $order->plan_name = $plan->name;
        $order->plan_id = $plan->id;
        $order->price = $amount;
        $order->price_currency = $currency;
        $order->txn_id = time();
        $order->payment_type = __('AuthorizetNet');
        $order->payment_status = 'success';
        $order->txn_id = '';
        $order->receipt = '';
        $order->user_id = $user->id;
        $order->save();
        $user = User::find($user->id);

        $assignPlan = $user->assignPlan($plan->id);


        if ($assignPlan['is_success']) {
            return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
        } else {
            return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
        }
    }

    public function invoicePayWithAuthorizeNet(Request $request)
    {
        $invoice_id = \Illuminate\Support\Facades\Crypt::decrypt($request->invoice_id);
        $invoice = Invoice::find($invoice_id);
        $user = User::find($invoice->created_by);

        $company_payment_setting = Utility::getCompanyPaymentSetting($user->id);
        $currency = isset($company_payment_setting['site_currency']) ? $company_payment_setting['site_currency'] : 'USD';
        $settings = Utility::settingsById($invoice->created_by);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $get_amount = $request->amount;
        $payment_id = $invoice->id;

        try {
            if ($invoice) {
                $data = [
                    'invoiceID'     =>  $invoice_id,
                    'user_id'       =>  $user->id,
                    'get_amount'    =>  $get_amount,
                    'authuser'      =>  $user,
                ];

                $data  =    json_encode($data);

                try {
                    return view('invoice.request', compact('invoice', 'get_amount','user','data'));
                } catch (\Exception $e) {
                    dd($e);
                    \Log::error($e->getMessage());
                }
            } else {
                return redirect()->back()->with('error', 'Invoice not found.');
            }
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', __($e));
        }
    }

    public function getInvoicePaymentStatus(Request $request)
    {
        $input          = $request->all();
        $data           = json_decode($input['data'] , true);

        $invoice_id     =   $data['invoiceID'];
        $amount         =   $data['get_amount'];
        $invoice = Invoice::find($invoice_id);
        $user = User::find($invoice->created_by);

        $settings= Utility::settingsById($invoice->created_by);
        if ($invoice)
        {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            try
            {
                    $invoice_payment                 = new InvoicePayment();
                    $invoice_payment->invoice_id     = $invoice_id;
                    $invoice_payment->date           = Date('Y-m-d');
                    $invoice_payment->amount         = $amount;
                    $invoice_payment->account_id         = 0;
                    $invoice_payment->payment_method         = 0;
                    $invoice_payment->order_id      =$orderID;
                    $invoice_payment->payment_type   = 'AuthorizeNet';
                    $invoice_payment->receipt     = '';
                    $invoice_payment->reference     = '';
                    $invoice_payment->description     = 'Invoice ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id);
                    $invoice_payment->save();

                    if($invoice->getDue() <= 0)
                    {
                        $invoice->status = 4;
                        $invoice->save();
                    }
                    elseif(($invoice->getDue() - $invoice_payment->amount) == 0)
                    {
                        $invoice->status = 4;
                        $invoice->save();
                    }
                    else
                    {
                        $invoice->status = 3;
                        $invoice->save();
                    }
                    //for customer balance update
                    Utility::updateUserBalance('customer', $invoice->customer_id, $request->amount, 'debit');

                    //For Notification
                    $customer = Customer::find($invoice->customer_id);
                    $notificationArr = [
                            'payment_price' => $request->amount,
                            'invoice_payment_type' => 'Aamarpay',
                            'customer_name' => $customer->name,
                        ];
                    //Slack Notification
                    if(isset($settings['payment_notification']) && $settings['payment_notification'] ==1)
                    {
                        Utility::send_slack_msg('new_invoice_payment', $notificationArr,$invoice->created_by);
                    }
                    //Telegram Notification
                    if(isset($settings['telegram_payment_notification']) && $settings['telegram_payment_notification'] == 1)
                    {
                        Utility::send_telegram_msg('new_invoice_payment', $notificationArr,$invoice->created_by);
                    }
                    //Twilio Notification
                    if(isset($settings['twilio_payment_notification']) && $settings['twilio_payment_notification'] ==1)
                    {
                        Utility::send_twilio_msg($customer->contact,'new_invoice_payment', $notificationArr,$invoice->created_by);
                    }
                    //webhook
                    $module ='New Invoice Payment';
                    $webhook=  Utility::webhookSetting($module,$invoice->created_by);
                    if($webhook)
                    {
                        $parameter = json_encode($invoice_payment);
                        $status = Utility::WebhookCall($webhook['url'],$parameter,$webhook['method']);
                        if($status == true)
                        {
                            return redirect()->route('invoice.link.copy', \Crypt::encrypt($invoice->id))->with('error', __('Transaction has been failed.'));
                        }
                        else
                        {
                            return redirect()->back()->with('error', __('Webhook call failed.'));
                        }
                    }
                    return redirect()->route('invoice.link.copy', \Crypt::encrypt($invoice->id))->with('success', __('Invoice paid Successfully!'));
            }
            catch (\Exception $e)
            {
                return redirect()->route('invoice.link.copy', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success',$e->getMessage());
            }
        } else {
            return redirect()->route('invoice.link.copy', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Invoice not found.'));
        }

    }
}
