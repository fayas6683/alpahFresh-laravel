<?php

/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 8/6/15
 * Time: 9:52 AM
 */
class FakerController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {

    }

    public function getAllVenuesByClubOwner()
    {
        try {

            $id = Authorizer::getResourceOwnerId();

            $venues = Venue::with('venueType')->where('user_id', '=', $id)->get();

            return Response::json(array('status' => 'success', 'venues' => $venues), 200);

        } catch (Exception $e) {

            return Response::json(array('status' => 'error', 'error' => $e->getMessage()), 403);
        }

    }

    public function generateVenues()
    {
        $arr = array('Friday', 'Saturday', 'Sunday');

        $clubOwners = DB::table('users_groups')
            ->leftJoin('users', 'users.id', '=', 'users_groups.user_id')
            ->where('users_groups.group_id', '=', 1)
            ->select(array('users.id'))
            ->get();

        foreach ($clubOwners as $owner) {


            $venuesOwned = Venue::where('user_id', '=', $owner->id)->count();

            for ($i = 0; $i < (3 - $venuesOwned); $i++) {
                $venue = new Venue;
                $faker = Faker\Factory::create();

                $venue->user_id = $owner->id;
                $venue->name = $faker->company;
                $venue->capacity = rand(50, 300);
                $venue->address = $faker->address;
                $venue->country = 'Australia';
                $venue->venue_type_id = rand(1, 7);
                $venue->save();

                foreach ($arr as $day) {


                    if ($day != 'Sunday') {

                        $businssHours = new BusinessHours;

                        $businssHours->day = $day;
                        $businssHours->venue()->associate($venue);
                        $businssHours->start_time = '22:00:00';
                        $businssHours->end_time = '00:00:00';
                        $businssHours->save();

                    }

                    if ($day != 'Friday') {

                        $businssHours2 = new BusinessHours;

                        $businssHours2->day = $day;
                        $businssHours2->venue()->associate($venue);
                        $businssHours2->start_time = '00:00:00';
                        $businssHours2->end_time = '05:00:00';
                        $businssHours2->save();

                    }

                }

            }
        }

    }

    public function generateUsers()
    {

        try {

            for ($i = 0; $i < 50; $i++) {

                $faker = Faker\Factory::create();

                $value = rand(0,1);

                if($value == 1 ){

                    $activeStatus = true;

                }else
                {
                    $activeStatus = false;
                }

                $user = Sentry::createUser(array(
                    'email' => $faker->email,
                    'username' => $faker->userName,
                    'password' => $faker->password,
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'billing_region' => $faker->country,
                    'zip_code' => $faker->postcode,
                    'state'      => $faker->state,
                    'city' => $faker->city,
                    'address_line_1' => $faker->address,
                    'activated' => $activeStatus,
                ));

                $adminGroup = Sentry::findGroupById(2);
                $user->addGroup($adminGroup);
                $user->save();

            }

        } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
            return Response::json(array('status' => 'success', 'message' => 'Login field is required.'), 500);
        } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
            return Response::json(array('status' => 'success', 'message' => 'Password field is required.'), 500);
        } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
            return Response::json(array('status' => 'success', 'message' => 'User with this login already exists.'), 500);
        } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
            return Response::json(array('status' => 'success', 'message' => 'Group was not found.'), 500);
        }
    }


    public function generateStaff()
    {
        try {

            $clubOwners = DB::table('users_groups')
                ->leftJoin('users', 'users.id', '=', 'users_groups.user_id')
                ->where('users_groups.group_id', '=', 1)
                ->select(array('users.id'))
                ->get();

            foreach ($clubOwners as $owner) {


                $venuesOwned = UsersStaff::where('user_id', '=', $owner->id)->count();

                $clubOwner = \Cartalyst\Sentry\Users\Eloquent\User::find($owner->id);

                for ($i = 0; $i < (3 - $venuesOwned); $i++) {


                    $faker = Faker\Factory::create();

                    $user = Sentry::createUser(array(
                        'email' => $faker->email,
                        'username' => $faker->userName,
                        'password' => $faker->password,
                        'first_name' => $faker->firstName,
                        'last_name' => $faker->lastName,
                        'activated' => true,
                    ));

                    $adminGroup = Sentry::findGroupById(2);
                    $user->addGroup($adminGroup);
                    $user->save();


                    $venuesAssigned = UsersStaff::where('user_id', '=', $owner->id)->select('venue_id')->get();
                    $arrayVenuesId = array();

                    foreach ($venuesAssigned as $val) {

                        array_push($arrayVenuesId, $val->venue_id);
                    }

                    $venueNotIn = Venue::where('user_id', '=', $owner->id)
                        ->where(function ($query) use ($arrayVenuesId) {
                            $query->whereNotIn('id', $arrayVenuesId);
                        })
                        ->first();

                    $userStaff = new  UsersStaff;

                    $userStaff->venue()->associate($venueNotIn);
                    $userStaff->staff()->associate($user);
                    $userStaff->user()->associate($clubOwner);
                    $userStaff->save();

                }

            }

        } catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
            return Response::json(array('status' => 'success', 'message' => 'Login field is required.'), 500);
        } catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
            return Response::json(array('status' => 'success', 'message' => 'Password field is required.'), 500);
        } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
            return Response::json(array('status' => 'success', 'message' => 'User with this login already exists.'), 500);
        } catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
            return Response::json(array('status' => 'success', 'message' => 'Group was not found.'), 500);
        }
    }


    public  function isOpenAtThisTime($day, $month, $hour,$minute,$second )
    {

        $dateTime = '2015-' . $month . '-' . $day.' '.$hour.':'.$minute.':'.$second;
        $dayOfTheDate = date('D', strtotime($dateTime));

     //   echo $dateTime."<br>";
       // echo strtotime($dayOfTheDate);
        //echo $dayOfTheDate."<br>";

        if ($dayOfTheDate == 'Fri') {
            if (($hour >= 22 & $hour < 24)) {
                return true;
            }else {
                return false;
            }

        }else if($dayOfTheDate == 'Sat') {


            if (($hour >= 22 & $hour < 24) || ($hour >= 0 & $hour <= 5)) {
                return true;
            }else {
                return false;
            }

        } else if($dayOfTheDate == 'Sun'){
            if (($hour >= 0 & $hour <= 5)) {
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }


    public function generateNightStats()
    {

        ini_set('max_execution_time', 30000000);

        $userStaff = DB::table('users_groups')
            ->leftJoin('users', 'users.id', '=', 'users_groups.user_id')
            ->where('users_groups.group_id', '=', 2)
            ->select(array('users.id'))
            ->get();

        foreach ($userStaff as $usf) {

            $staff   = \Cartalyst\Sentry\Users\Eloquent\User::find($usf->id);
            $venueId = UsersStaff::where('staff_id', '=', $usf->id)->first();
            $venue = Venue::find($venueId->venue_id);

            for ($I = 0; $I < 10000; $I++) {

                for ($month = 1; $month <= 12; $month++) {


                    for ($day = 1; $day <= 31; $day++) {

                        for ($hour = 0; $hour < 24; $hour++) {

                            $minute = rand(0, 60);
                            $second = rand(0, 60);

                            if ($this->isOpenAtThisTime($day, $month, $hour,$minute,$second)) {


                                $probability = rand(0, 100);
                                $going_in = false;
                                $going_out = false;


                                if ($probability > 70) {
                                    if (($hour > 23 & $hour < 24) || ($hour > 0 & $hour <= 1)) {

                                        $going_in = true;
                                    } else {
                                        $going_out = true;
                                    }

                                } else {
                                    if (($hour > 23 & $hour < 24) || ($hour > 0 & $hour <= 1)) {

                                        $going_out = true;
                                    } else {
                                        $going_in = true;
                                    }
                                }

                                $nightStatistic = new NightStatistic;

                                $nightStatistic->user()->associate($staff);
                                $nightStatistic->venue()->associate($venue);
                                $nightStatistic->date = '2015-' . $month . '-' . $day;
                                $nightStatistic->hour = $hour.':'.$minute.':'.$second;

                                $randProbGender = rand(0, 100);
                                $nightStatistic->is_female = false;
                                $nightStatistic->is_male = false;

                                if($randProbGender < 50) {
                                    $nightStatistic->is_female = true;
                                }else {
                                    $nightStatistic->is_male = true;
                                }

                                if($going_in){

                                    $nightStatistic->is_entering = $going_in;
                                }

                                if($going_out){

                                    $nightStatistic->is_entering = $going_out;
                                }

                                $nightStatistic->save();

//                                echo "g_o=".$going_out;
//                                echo "g_i=".$going_in;
                                $dateTime = '2015-' . $month . '-' . $day.' '.$hour.':'.$minute.':'.$second;


//                                echo $dateTime;
                                $dayOfTheDate = date('D', strtotime($dateTime));
//                                echo "day=".$dayOfTheDate."<br/>";
//                                echo strtotime($dayOfTheDate)."<br/>";
//                                //echo strtotime($dateTime);
//                                die();

                            }
                        }

                    }

                }


            }

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


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

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

    }


}

