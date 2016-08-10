<?php

/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 2/19/15
 * Time: 3:39 PM
 */
class EmailController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        return Email::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(

            //'subject' => 'required',
            //'email_id' => 'required',
            //'message' => 'required',
            //'for_id' => 'required',
            //'receiver_type_id' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $email = new Email;

            $email->subject  = 'Ship this product ';
            $email->email_id = Input::get('receiver_email_id');
            $email->for_id   = Input::get('receiver_id');

            $message = Input::get('message');
            $email->message = $message;

            $receiver = Input::get('receiver_email_id');

            $emailRecipients = array('email' => $receiver, 'first_name' => 'John Smith', 'from' => 'admin@diamatic.com.au', 'from_name' => 'Admin', 'subject' => $email->subject);

            Mail::send('emails.OrderConfirmationToVendorEmail', array('msg' => $email->message), function ($message) use ($emailRecipients) {
                $message->from($emailRecipients['from'], $emailRecipients['from_name']);

                $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
            });

            $email->save();


        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return Email::find($id);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $email = Email::find($id);
        $email->delete();
    }



        public function contactUs()
    {
        $rules = array(
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'body' => 'required',
            'userType' => 'required'
            );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json($validator->messages(), 500);
        } else {
            $name = Input::get('name');
            $email = Input::get('email');
            $subject = Input::get('subject');
              $body = Input::get('body');
               $userType = Input::get('userType');
            $to = Config::get('app.sender_info');
            $arrayData = array(
                'name'        => $name,
                'subject'   => $subject,
                'body'   => $body,
                'admin'  => 'Admin',
                'emails' => $email,
                'userType' = >$userType
                );
            $emailRecipients = array('email' => $to , 'first_name' => $name  , 'from' => $email , 'from_name' => $name, 'subject' => $subject);
            Mail::send('emails.contactUs', $arrayData, function ($message) use ($emailRecipients) {
                $message->from($emailRecipients['from'], $emailRecipients['from_name']);
                $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']);
            });
            return Response::json(array('status' => 'success' ,'data' =>  Input::all() ),200);
        }
    }


}

