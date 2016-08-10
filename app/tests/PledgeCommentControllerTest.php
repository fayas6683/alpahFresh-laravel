<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 9/30/15
 * Time: 4:42 PM
 */

class PledgeCommentControllerTest extends TestCase {


    public function __construct()
    {
        // We have no interest in testing Eloquent
        $this->mock = Mockery::mock('User');

    }

    public function testBasicExample(){

        Route::enableFilters();

        $pledge = Pledge::find(15);
        $tier   = PledgeTiers::find(20);

//        $response = $this->action('GET', 'PledgeController@show',array('id' => 11 ));

        $userStaff = DB::table('users_groups')
                        ->leftJoin('users', 'users.id', '=', 'users_groups.user_id')
                        ->where('users_groups.group_id', '=', 2)
                        ->whereNotNull('users.id')
                        ->where('users.id','>=', 256 )
                        ->select(array('users.id'))
                        ->get();


        foreach($userStaff as $usr ){

            echo $user_id = $usr->id;

            $user = Sentry::findUserById($user_id);

            $response = $this->call('POST', 'http://192.168.1.7:8000/api/v1/oauth/access_token',array('grant_type'=> 'password','client_id'=>1,'client_secret' => 123, 'username' => $user->username ,'password' => 'password'));
//            $this->assertResponseStatus(200);

            $access_token = $response->getData()->access_token;
            $remember = $this->call('GET', 'http://192.168.1.7:8000/api/v1/rememberedCardDetails', array(), array(), array('HTTP_AUTHORIZATION' => $access_token));

            if($remember->original['remembered']){

                $paymentDetails      = $this->call('GET','http://192.168.1.7:8000/api/v1/getPaymentMethodDetailsByCustomer', array(), array(), array('HTTP_AUTHORIZATION' => $access_token));
                $billingInformation  = $this->call('GET','http://192.168.1.7:8000/api/v1/getBillingInformation', array(), array(), array('HTTP_AUTHORIZATION' => $access_token));
                $orderShipping       = $this->call('GET','http://192.168.1.7:8000/api/v1/orderShipping', array('product_id' => $pledge->product_id , 'amount' => $tier->ammount ), array(), array('HTTP_AUTHORIZATION' => $access_token));

                $arrayShippingCosts  = $orderShipping->getData()->rates;
                $indexRandomShipping = array_rand($arrayShippingCosts);
                $randomShipping      = $arrayShippingCosts[$indexRandomShipping];

                $pledgeToTier       = $this->call('POST','http://192.168.1.7:8000/api/v1/pledgeToTier', array('carrier' => $randomShipping->carrier , 'service' => $randomShipping->service ,'pledge_tier_id' => 20 ), array(), array('HTTP_AUTHORIZATION' => $access_token));

            }
            // else store credit card details.

        }

    }

}
 