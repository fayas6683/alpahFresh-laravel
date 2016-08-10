<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 11/17/15
 * Time: 10:31 AM
 */

class MediaControllerTest extends \Codeception\TestCase\Test {

    /**
     * @var \ApiTester
     */
    protected $tester;

    /** @test */
    public function fetch_all_medias()
    {
        // Use $I instead od $this->tester
        $I = $this->tester;

        // Send GET request to url
        $I->sendGET('/media');

        // See response is valid JSON
        $I->seeResponseIsJson();

        // See response code is 200
        $I->seeResponseCodeIs(200);

        // See response contains text `data`
        $I->seeResponseContains('data');
    }


    public function fetch_create_media()
    {
        $I = $this->tester;

        $I->sendPOST('/media',['name' => 'davert', 'media_category_id' => 1 , 'file_path' => '/uploads/media_images/bfsksfsk3433.png', 'file_name'  => 'office' , 'file_type' => '.png' , 'media_image' => 'data:image/gif;base64,R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==']);

        $I->seeResponseIsJson();

        // See response code is 200
        $I->seeResponseCodeIs(200);

        // See response contains text `data`
        $I->seeResponseContains('data');
    }


}