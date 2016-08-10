    <?php

    /**
     * Created by PhpStorm.
     * User: Diamatic.ltd
     * Date: 12/12/14
     * Time: 11:49 AM
     */
    //Stripe::setApiKey('sk_test_OkiTAQeCfaucYe9EML0nFTRf');


    class UsersController extends BaseController
    {

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */


        public function index()
        {

         try {
                 $groupId = Input::get('group_id');
                $page = Input::get('page');



                 $search = Input::get('search');
                 $id = Authorizer::getResourceOwnerId();
                 $users = \Cartalyst\Sentry\Users\Eloquent\User::whereHas('groups', function($query) use($groupId)

                 {
                   $query->where('id', '=',$groupId ); 
                 }
                 );


              if($search) 
             {
              $users = $users->where(function ($query) use($search) {
                
                   $query->orWhere('first_name', 'like',"%{$search}%" );
                   $query->orWhere('last_name', 'like',"%{$search}%" );
                   $query->orWhere('email', 'like',"%{$search}%" );
      
                    });
             }


                   $users = $users->orderBy('created_at','desc');

                if($page)
                {
                   $users = $users->Paginate(Config::get('app.user_per_page'));
                }
              else{
                   $lenght = $users->count();

                 $users = $users->Paginate( $lenght);
                }
                foreach($users as $user )
                {
                $throttle       = Sentry::findThrottlerByUserId($user->id);
                  if( $throttle->isBanned())
                  {
                    $user->ban = 1;
                  }
                  else{
                    $user->ban = 0;
                  }
                }
           return Response::json(array('status' => 'success' , 'data' => $users->toArray(),  'page'=>$users->paginateObject()),200);

        } 

         Catch (Exception $ex) {
            return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()));
        } 


        }

        public function checkAuthentication(){

            try {

                $id = Authorizer::getResourceOwnerId();

                return Response::json(array('status' => 'success' , 'data' => $id ),200);

            }catch (Exception $ex)
            {
                return Response::json(array('status' => 'error' , 'data' => $ex->getMessage() ),500);
            }

        }

        public function banUser(){

            try
            {
                $user_id        = Input::get('user_id');
                $throttle       = Sentry::findThrottlerByUserId($user_id);

                $throttle->ban();

                return Response::json(array('status' => 'success' ),200);
            }
            catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
            {
                return Response::json(array('status' => 'error', 'error' => 'user not found '),500);
            }

        }

        public function unBanUser(){

            try
            {
                $user_id        = Input::get('user_id');
                $throttle       = Sentry::findThrottlerByUserId($user_id);

                $throttle->unBan();

                return Response::json(array('status' => 'success' ),200);

            }
            catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
            {
                return Response::json(array('status' => 'error', 'error' => 'user not found '),500);
            }
        }



    



              public function getCurrentUser()
        {

            $id = Authorizer::getResourceOwnerId();

            if ($id) {

                $user = Sentry::with('groups')->where('id','=',$id)->first();
                  return Response::json(array('status' => 'success' , 'data' => $user ),200);


            } else {

                App::abort(403, 'User not authenticated.');
            }
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

        public function userCreate()
        {


            $rules = array(

                'group_id'      => 'required',
                'password'      => 'required|min:6',
                'email'         => 'required|email|unique:users',
                'first_name'    => 'required',
                'last_name'     => 'required',

            );
            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {

                return Response::json($validator->messages(), 500);

            } else {

                try {


                    if ( Input::get('group_id') == Config::get('app.group_id_consumer') ) {

                      $user = Sentry::createUser(array(

                            'first_name'        => Input::get('first_name'),
                            'last_name'         => Input::get('last_name'),
                            'email'               => Input::get('email'),
                            'username'           => Input::get('email'),
                            'phone'               =>Input::get('phone'),
                            'storeName'         => Input::get('storeName'),
                            'street'              => Input::get('street'),
                            'city'                 => Input::get('city'),
                            'zip_code'            => Input::get('zip_code'),
                            'country'             => Input::get('country'),
                            'password'            => Input::get('password'),
                            'activated'           => false,
                        ));


                   
                        $adminGroup = Sentry::findGroupById(Input::get('group_id'));
                        $user->addGroup($adminGroup);


                            $user->save();

                            $activationCode = $user->getActivationCode();

                            $array = array(

                                'link'              => Config::get('app.domain_name').'#/registerConfirmation/'.$activationCode,
                                'first_name'      => $user->first_name,
                                'last_name'       => $user->last_name,
                                'email'             => Config::get('app.sender_info')
                            );

                     sendActivationEmail($user->email, 'You have registered to Silco', $array);

                    }

                    return Response::json(array('status' => 'success', 'data' => $user ), 200);

                } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {

                    $group = Sentry::findGroupById(Input::get('group_id'));
                    $user->removeGroup($group);
                    $user->delete();

                    return Response::json(array('status' => 'error', 'error' => 'Login field is required.'), 500);
                } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {

                    $group = Sentry::findGroupById(Input::get('group_id'));
                    $user->removeGroup($group);
                    $user->delete();

                    return Response::json(array('status' => 'error', 'error' => 'Password field is required.'), 500);
                } catch (Cartalyst\Sentry\Users\UserExistsException $e) {

                    $group = Sentry::findGroupById(Input::get('group_id'));
                    $user->removeGroup($group);
                    $user->delete();

                    return Response::json(array('status' => 'error', 'error' => 'User with this login already exists.'), 500);
                } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {

                    $group = Sentry::findGroupById(Input::get('group_id'));
                    $user->removeGroup($group);
                    $user->delete();
                    return Response::json(array('status' => 'error', 'error' => 'Group was not found.'), 500);

                }catch(Exception $ex ) {

                    $group = Sentry::findGroupById(Input::get('group_id'));
          
                    return Response::json(array('status' => 'error', 'error' => $ex->getMessage()  ), 500);
                }

            }
        }

        /**
         * Store a newly created resource in storage.
         *
         * @return Response
         */

        
        public function store()
        {
            $rules = array(

                'group_id'      => 'required',
                'username'      => 'required|unique:users',
                'password'      => 'required|min:6',
                'email'         => 'required|email|unique:users',
                'first_name'    => 'required',
                'last_name'     => 'required',
                'phone'          => 'required'

            );
             $id = Authorizer::getResourceOwnerId();
        $userOwner   = \Cartalyst\Sentry\Users\Eloquent\User::find($id);
            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {

                return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

            } else {

                try {

                        $user = Sentry::createUser(array(

                                    'email'               => Input::get('email'),
                                    'username'          => Input::get('username'),
                                    'password'          => Input::get('password'),
                                    'first_name'        => Input::get('first_name'),
                                    'last_name'         => Input::get('last_name'),
                                    'phone'               => Input::get('phone'),
                                    'activated'          => true,
                            ));

                $group = Sentry::findGroupById(Input::get('group_id'));
                $user->addGroup($group );
                $user->save();

                 if ( Input::get('group_id') == Config::get('app.group_id_staff') ) {
                            $userStaff = new  UsersStaff;
                            $userStaff->staff()->associate($user);
                            $userStaff->user()->associate($userOwner);
                            $userStaff->save();
                            $activationCode = $user->getActivationCode();

                            $array = array(

                                'link'              => Config::get('app.domain_name').'#/login',
                                'first_name'        => $user->first_name,
                                'last_name'         => $user->last_name,
                                'email'             => Config::get('app.sender_info'),
                                'staffEmail' => $user->email,
                                'password' => Input::get('password')
                            );
                            sendEmailLoginDetailsToStaff($user->email, 'You have registered to Silco', $array);
                    }

                    return Response::json(array('status' => 'success', 'data' => $user ), 200);

                } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {

                    $group = Sentry::findGroupById(Input::get('group_id'));
                    $user->removeGroup($group);
                    $user->delete();

                    return Response::json(array('status' => 'error', 'error' => 'Login field is required.'), 500);
                } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {

                    $group = Sentry::findGroupById(Input::get('group_id'));
                    $user->removeGroup($group);
                    $user->delete();

                    return Response::json(array('status' => 'error', 'error' => 'Password field is required.'), 500);
                } catch (Cartalyst\Sentry\Users\UserExistsException $e) {

                    $group = Sentry::findGroupById(Input::get('group_id'));
                    $user->removeGroup($group);
                    $user->delete();

                    return Response::json(array('status' => 'error', 'error' => 'User with this login already exists.'), 500);
                } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {

                    $group = Sentry::findGroupById(Input::get('group_id'));
                    $user->removeGroup($group);
                    $user->delete();
                    return Response::json(array('status' => 'error', 'error' => 'Group was not found.'), 500);

                }catch(Exception $ex ) {

                    $group = Sentry::findGroupById(Input::get('group_id'));
                    $user->removeGroup($group);
                    $user->delete();
                    return Response::json(array('status' => 'error', 'error' => $ex->getMessage()  ), 500);
                }

            }
        }

        public function activateUser()
     {
    try
    {
        $activationCode = Input::json()->get('activation_code');
        $user = Sentry::findUserByActivationCode($activationCode);
        if ($user->attemptActivation($activationCode))
        {
            return Response::json(array('status' => 'success' ,'data' => 'User activation passed' ),200);
        }
        else
        {
            return Response::json(array('status' => 'success' ,'data' => 'User activation failed' ),500);
        }
    }
    catch (Cartalyst\Sentry\Users\UserNotFoundException   $e)
    {
        return Response::json(array('status' => 'error' ,'data' => $e ),500);
    }
    catch (Cartalyst\Sentry\Users\InvalidArgumentException $f)
    {
        return Response::json(array('status' => 'error' ,'data' => $f ),500);
    }
    catch (Cartalyst\Sentry\Users\RuntimeException $f)
    {
        return Response::json(array('status' => 'error' ,'data' => $f ),500);
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
            $user = \Cartalyst\Sentry\Users\Eloquent\User::find($id);
            return $user->toJson();
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
            $rules = array(
                //'group_id' => 'required', // chk
                //'username' => 'required|unique:user,username,' . $id,
                'email' => 'required|unique:users,email,' . $id,
                'first_name' => 'required',
                'last_name' => 'required',
                //'password' => 'sometimes|required|min:6'
                //'active' => 'required|numeric'

            );
            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {

                return Response::json($validator->messages(), 500);

            } else {

                try
                {
                    $user                   = Sentry::findUserById($id);
                    $user->email          = Input::get('email');
                    $user->first_name    = Input::get('first_name');
                    $user->last_name     = Input::get('last_name');
                    $user->street          = Input::get('street');
                    $user->city             = Input::get('city');
                    $user->zip_code        = Input::get('zip_code');
                    $user->phone            = Input::get('phone');
                    $user->storeName       = Input::get('storeName');
                    $user->country          = Input::get('country');

                    $password                 = Input::get('password');
                    $foo                         = Input::get('file');

                    if(!(strpos($foo, 'base64'))) $foo = null;

                    if(!empty($foo)){

                        $oldAvatar                        = $user->avatar;
                        if(!empty($oldAvatar)){

                            File::delete(public_path($oldAvatar));
                        }

                        $oldProfile_pic                   = $user->profile_pic;
                        if(!empty($oldProfile_pic)){

                            File::delete(public_path($oldProfile_pic));
                        }


                        $time                             = time();
                        $destinationPath                  = 'uploads/profile_pics/'.$user->username.'_'.$time.'_avatar.jpg';
                        $arrayBase64String                = explode(",", $foo );

                        $image                            = base64_decode($arrayBase64String[1]);
                        file_put_contents(public_path($destinationPath),  $image );
                        $picture                          = Image::make(public_path($destinationPath));
                        $destinationPathProfilePic        = 'uploads/profile_pics/'.$user->username.'_'.$time.'_profile_pic.jpg';
                        $picture->fit(128,128)->save(public_path($destinationPathProfilePic));
                        $picture->fit(50,50)->save(public_path($destinationPath));



                        $user->avatar                     = $destinationPath;
                        $user->profile_pic                = $destinationPathProfilePic;
                    }

                    if (!empty($password)) {

                        $user->password = $password;
                    }
                    $user->save();
                    return Response::json(array('status' => 'success', 'user' => $user ), 200);
                }
                catch (Exception $ex) {

                    return Response::json(array('status' => 'error', 'error' => $ex->getMessage() , 'line' => $ex->getLine() ), 500);
                }

            }
        }


        /**
         * Remove the specified resource from storage.
         *
         * @param  int $id
         * @return Response
         */
        public function destroy($id)
        {
          
              try {
      
                 $user = \Cartalyst\Sentry\Users\Eloquent\User::find($id);
             if(!empty($user)){
                $user->delete();
                return Response::json(array('status' => 'success' , 'message' => 'Successfully deleted ! ' ),200);
            }
            }
        catch (Exception $ex ){
            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ),500);
        }

        }

    }


    function sendRegisterConfirmationEmail($user, $subject, $arrayMessage)
    {

        $emailRecipients = array('email' => $user->email, 'first_name' => 'John Smith', 'from' => 'support@noblecrowd.com', 'from_name' => 'Admin', 'subject' => $subject);


        Mail::send('emails.RegistrationConfirmEmail', $arrayMessage, function ($message) use ($emailRecipients) {
            $message->from($emailRecipients['from'], $emailRecipients['from_name']);

            $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']); // tony.t.lucas@gmail.com
        });


        $email              = new Email;

        $email->email_id    = $user->email;
        $email->subject     = $emailRecipients['subject'];
        //$email->message  = $message;
        $email->save();
    }








    function sendActivationEmail($email, $subject, $array)
    {

        $emailRecipients = array('email' => $email, 'first_name' => Config::get('app.sender_info') , 'from' => Config::get('app.sender_info') , 'from_name' => 'Silco', 'subject' => $subject);

        Mail::send('emails.ActivationEmail', $array, function ($message) use ($emailRecipients) {
            $message->from($emailRecipients['from'], $emailRecipients['from_name']);

            $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']);
        });

    }


function sendEmailLoginDetailsToStaff($email, $subject, $array)
    {

        $emailRecipients = array('email' => $email, 'first_name' => Config::get('app.sender_info') , 'from' => Config::get('app.sender_info') , 'from_name' => 'Silco', 'subject' => $subject);

        Mail::send('emails.StaffLoginInformation', $array, function ($message) use ($emailRecipients) {
            $message->from($emailRecipients['from'], $emailRecipients['from_name']);

            $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']);
        });

    }