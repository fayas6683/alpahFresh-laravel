<?php


class RemindersController extends Controller
{

    /**
     * Display the password reminder view.
     *
     * @return Response
     */
    public function getRemind()
    {
        return View::make('password.remind');
    }

    /**
     * Handle a POST request to remind a user of their password.
     *
     * @return Response
     */

    public function getPasswordResetCode()
    {

        try {
            $user = \Cartalyst\Sentry\Facades\Native\Sentry::findUserByLogin(Input::only('email'));



            $resetCode = $user->getResetPasswordCode();
;
            $array = array(

                'code'          => $resetCode,
                'domain_name'   => Config::get('app.domain_name')
            );

            //return $html =  View::make('emails.auth.reminder',$array);
            $subject = 'Password Reset';
            sendPasswordResetEmail($user, $subject, $array);

            return Response::json(array('status' => 'success', 'code' => $resetCode));
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return Response::json(array('status' => 'error'));
        }
    }

    public function postRemind()
    {

        Password::remind(Input::only('email'), function ($message) {
            $message->subject('Password Reminder');

            switch ($response = Password::remind(Input::only('email'))) {
                case Password::INVALID_USER:
                    return Response::json(array('status' => 'error', 'msgs' => 'user doesnt exist'), 402);

                case Password::REMINDER_SENT:
                    return Response::json(array('status' => 'Success', 'msgs' => 'email sent to you'), 200);
            }
        });


    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string $token
     * @return Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) App::abort(404);

        return View::make('password.reset')->with('token', $token);
    }

    /**
     * Handle a POST request to reset a user's password.
     *
     * @return Response
     */
    public function postReset()
    {
        $credentials = Input::only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $user = \Cartalyst\Sentry\Users\Eloquent\User::where('email', '=', $credentials['email'])->first();

        if ($user->checkResetPasswordCode($credentials['token'])) {

            if ($user->attemptResetPassword($credentials['token'], $credentials['password'])) {


                return Response::json(array('status' => 'success'), 200);

            } else {
                return Response::json(array('status' => 'failed'), 403);
            }
        } else {
            return Response::json(array('status' => 'invalid token'), 403);
        }



    }

}

function sendPasswordResetEmail($user, $subject, $array)
{

    $emailRecipients = array('email' => $user->email, 'first_name' => Config::get('app.sender_info'), 'from' => Config::get('app.sender_info') , 'from_name' => 'Personal Pages', 'subject' => $subject);

    Mail::send('emails.auth.reminder', $array, function ($message) use ($emailRecipients) {
        $message->from($emailRecipients['from'], $emailRecipients['from_name']);

        $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
    });


}