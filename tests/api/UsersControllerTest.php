<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 11/17/15
 * Time: 1:20 PM
 */

class UsersControllerTest extends \Codeception\TestCase\Test {

    /**
     * @var \ApiTester
     */
    protected $tester;

    /** @test */


    public function fetch_create_media()
    {

        $I = $this->tester;

        $I->wantTo('login user by API');

        $I->sendPOST('/oauth/access_token', array('username' => 'consumer' , 'password' => 'password', 'client_id' => 1 , 'grant_type' => 'password' , 'client_secret' => 123));

        $I->seeResponseIsJson();

        $I->seeResponseCodeIs(200);

//        $I->seeResponseContainsJson(array('result' => 'ok'));

    }


}



