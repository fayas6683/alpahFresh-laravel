<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 5/21/15
 * Time: 1:44 PM
 */


class WebhookController extends \Laravel\Cashier\WebhookController {

    public function handleWebhook()
    {
        $payload = $this->getJsonPayload();

        if( $payload['type'] == 'charge.succeeded')
        {
            $this->handleChargeSucceeded($payload);

        }else if ($payload['type'] == 'invoice.payment_succeeded')
        {
            $this->handleInvoicePaymentSucceeded($payload);
        }
    }


    public function handleChargeSucceeded(array $payload)
    {

        print_r($payload['data']['object']['customer']);

//        $user = \Cartalyst\Sentry\Users\Eloquent\User::where('stripe_customer_id','=',$payload['data']['object']['customer'])->first();
//
//
//        $invoiceSubscription             = new Invoice;
//
//        $invoiceSubscription->invoice_id = 'in_'.time();
//        $invoiceSubscription->user_id    = $user->id;
//        $invoiceSubscription->subtotal   = $payload['data']['object']['amount']/100;
//        $invoiceSubscription->total      = $payload['data']['object']['amount']->amount/100;
//        $invoiceSubscription->charge_id  = $payload['data']['object']['id'];
//        $invoiceSubscription->save();
//
//        return new Response($payload, 200);
    }


    public function handleInvoicePaymentSucceeded(array $payload)
    {

        $user       = \Cartalyst\Sentry\Users\Eloquent\User::where('stripe_customer_id','=',$payload['data']['object']['customer'])->first();
        $planName   = $payload['data']['object']['lines']['data'][0]['plan']['name'];
        $total      = $payload['data']['object']['total']/100;
        $startDate  = date("F j, Y",$payload['data']['object']['lines']['data'][0]['period']['start']);
        $endDate    = date("F j, Y",$payload['data']['object']['lines']['data'][0]['period']['end']);

        $invoiceSubscription                        = new Invoice;

        $invoiceSubscription->invoice_id            = $payload['data']['object']['id'];
        $invoiceSubscription->user_id               = $user->id;
        $invoiceSubscription->subtotal              = $payload['data']['object']['subtotal']/100;
        $invoiceSubscription->total                 = $total;
        $invoiceSubscription->charge_id             = $payload['data']['object']['charge'];
        $invoiceSubscription->invoice_description   = 'Invoice for charge of '.$total.'$ for '.
                                                       $planName.' plan, period of '.$startDate.' to '.$endDate;
        $invoiceSubscription->refunded   = 0;
        $invoiceSubscription->save();

        $arrayPaymentConfirmationMessage = array(

            'logo'                  => Config::get('app.url'),
            'plan'                  => $planName,
            'amount'                => $total,
            'plan_start_date'       => $startDate,
            'plan_end_date'         => $endDate,
            'consumer_name'         => $user->first_name,
            'sender_info'           => Config::get('app.sender_info'),

        );

        sendPaymentConfirmationEmail($user, 'Subscription Payment confirmation ', $arrayPaymentConfirmationMessage);


        return Response::json(array('status' => 'success' , 'data' => 'invoice added successfully' ),200);
    }

    //
}

function sendPaymentConfirmationEmail($user, $subject, $arrayMessage)
{

    $emailRecipients = array('email' => $user->email, 'first_name' => Config::get('app.sender_info') , 'from' => Config::get('app.sender_info') , 'from_name' => 'Personal Pages', 'subject' => $subject);


    Mail::send('emails.SubscriptionPaymentConfirmationEmail', $arrayMessage, function ($message) use ($emailRecipients) {
        $message->from($emailRecipients['from'], $emailRecipients['from_name']);

        $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']);
    });


    $email = new Email;

    $email->email_id = $user->email;
    $email->subject = $emailRecipients['subject'];
    $email->save();
}
