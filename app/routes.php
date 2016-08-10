<?php


Route::group(array('prefix' => 'api/v1'), function () {

    Route::get('getBanners','BannerController@getBanners');
    Route::resource('testWebhook','TestController');
    Route::resource('category','CategoryController');
    Route::resource('products','ProductController');
    Route::post('makeMainPicture','ProductController@makeMainPicture');
    Route::get('getAllProducts','ProductController@getAllProducts');
    Route::get('getProductById','ProductController@getProductById');
    Route::post('userCreate', 'UsersController@userCreate');
    Route::post('postUpload/{product_id}','ProductController@postUpload');
    Route::post('getPasswordResetCode','RemindersController@getPasswordResetCode');
    Route::post('postReset','RemindersController@postReset');
    Route::post('activateUser','UsersController@activateUser');
    Route::post('postRemind','RemindersController@postRemind');
    Route::get('searchPostalCode/{req}','SuburbController@searchPostalCode');
    Route::get('searchSuburb/{req}','SuburbController@searchSuburb');
    Route::resource('email', 'EmailController');
    Route::resource('tags','TagsController');
    Route::post('uploadImageFromEditor','ApplyController@uploadImageFromEditor');
    Route::post('upload','ApplyController@upload');
    Route::get('generateUsers','FakerController@generateUsers');
    Route::resource('faker','FakerController');
    Route::post('storeImage','ImageController@storeImage');
    Route::post('destroyFile','ImageController@destroyFile');
    Route::get('imageLoad','ImageController@imageLoad');
    Route::get('timelinedetail','OrderController@timelinedetail');

   Route::get('getStatus','TimelineController@getStatus');

    Route::post('storeFile','ImageController@storeFile');
    Route::post('contactUs','EmailController@contactUs');
   Route::post('processUpdate','TimelineController@processUpdate');

     Route::post('updateStatus','OrderController@updateStatus');
 Route::get('currentTimeline','OrderController@currentTimeline');
 Route::post('addFeedback','OrderController@addFeedback');
 


      Route::post('timelineMessage','TimelineController@timelineMessage');

    Route::resource('measurement','MeasurementController');
   
   Route::resource('brand','BrandController');

    Route::group(['before' => 'oauth'], function () {
        
        Route::resource('user', 'UsersController');
        Route::get('checkAuthentication','UsersController@checkAuthentication');
        Route::get('getCurrentUser', 'UsersController@getCurrentUser');
        Route::resource('order','OrderController');
    });

    Route::group(['before' => 'oauth|Vendor'], function () {

     Route::get('getAllOrderByConsumer','OrderController@getAllOrderByConsumer');
     Route::get('searchByTags','ProductController@searchByTags');

    });


    Route::group(['before' => 'oauth|GlobalAdmin,Staff'], function () {

    });

    Route::group(['before' => 'oauth|GlobalAdmin,Consumer'], function () {
        Route::get('getItemsByOrder','OrderController@getItemsByOrder');
          Route::resource('timeline','TimelineController');

    });

    Route::group(['before' => 'oauth|GlobalAdmin'], function () {

        Route::resource('banner','BannerController');
       
        Route::get('getAllVendors','UsersController@getAllVendors');
        Route::resource('group', 'GroupsController');
        Route::get('getAllStaff','UsersController@getAllStaff');
        Route::post('banUser','UsersController@banUser');
        Route::post('unBanUser','UsersController@unBanUser');
        Route::post('deleteImage','ProductController@deleteImage');
        Route::post('removeTagProduct','ProductController@removeTagProduct');
        Route::post('addTagProduct','ProductController@addTagProduct');


    });

      


    Route::group(['before' => 'oauth|GlobalAdmin,Consumer'], function () {
  
        Route::resource('sms','SmsController');
        Route::resource('suburb', 'SuburbController');
        Route::resource('country', 'CountryController');
  
        Route::get('getMenuByUser', 'MenuController@getMenuByUser');
        Route::resource('menu', 'MenuController');

    });





    Route::post('stripe/webhook','WebhookController@handleWebhook');
//    Route::post('stripe/webhook', 'WebhookController@handleInvoicePaymentSucceeded');
//    Route::post('stripe/failedPayment','WebhookController@handleInvoicePaymentFailed');


    Route::get('testMail', function () {

        Mail::send('emails.OrderConfirmationToVendorEmail', array('msg' => 'This is the body of my email'), function ($message) {
            $message->from('admin@diamatic.com.au', 'admin');
            $message->to('mohamedrks@gmail.com', 'John Smith')->subject('Welcome!'); // tony.t.lucas@gmail.com
        });

    });



    Route::post('oauth/access_token', function () {
        // Rely on the built-in Input class to get
        // input from whichever format it was sent as.
        $input = \Input::all();

        // Get the current Request and replace
        // its POST parameters
        $request = \Request::instance();

        $request->request->replace($input);

        // Make sure the OAuth2 Authorizer uses our custom
        // Request which has the parameters filled in
        Authorizer::setRequest($request);
        return Response::json(Authorizer::issueAccessToken());
    });

        Route::get('braintreeClientTokenPath', function () {
            
        $clientToken = Braintree_ClientToken::generate();
        return $clientToken;
    });


    Route::get('info', function() {
        $v = 0;
        $c = 0;
        echo $c.$v;

        return phpinfo();

    });


});

Route::get('thumbnail/{hash}' , function($hash){

    $customTemplate = CustomTemplate::where('hash_id','=',$hash)->first();

    if(!empty($customTemplate)){

        try {
            $command = 'phantomjs '.public_path('screenshot.js').' '.Config::get('app.api_url').$customTemplate->site_directory.'
                                         test.png 800px*600px 0.8';

            $process = exec('phantom '.public_path('screenshot.js').' '.Config::get('app.api_url').$customTemplate->site_directory.'
                                         test.png 800px*600px 0.8');



            $process->setTimeout(3600);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            print $process->getOutput();

            return Response::json(array('status' => 'success' , 'data' => 'Thumbnail Saved Successfully'),200);

        }catch (Exception $ex)
        {
            return Response::json(array('status' => 'error' , 'data' => $ex->getMessage()),500);
        }





    }else{

        return File::get(public_path() .'/'. 'index.html');
    }

});


Route::get('insertNames', function(){

    $json           = file_get_contents('https://raw.githubusercontent.com/dominictarr/random-name/master/first-names.json');
    $nameArray      = json_decode($json);

    foreach($nameArray as $item )
    {
        DB::table('person_names')->insert(
            ['name' => $item ]
        );
    }

    $x = DB::select('select * from person_names');

    return $x;
});






Route::get('test', function() {

    echo exec('phantomjs C:\PersonalPagesLatest\public/screenshot.js http://192.168.1.7:8078/uploads/customTemplates/4c6b7f52a729e2ee13e4c137f5f6e487/index.html C:\PersonalPagesLatest\public/uploads/customTemplates/4c6b7f52a729e2ee13e4c137f5f6e487/x.png 800px*600px 0.8');
});


App::missing(function($exception)
{
    return File::get(public_path() . '/index.html');
});
