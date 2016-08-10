<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => true,

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the Artisan command line tool. You should set this to the root of
	| your application so that it is used when running Artisan tasks.
	|
	*/
    
    'phantomjs_url' => 'http://personalpages.io',
    'url' => 'http://personalpages.io/images/logo-large.png',
    'site_url' => 'http://personalpages.io/',
    'site_name' => 'Personal Pages',
    'sender_info' => 'support@nero4me.com.au',
    'api_url' => 'http://personalpages.io/',
    'group_id_superadmin' => 1 ,
     'group_id_staff' => 3 ,
      'group_id_consumer' => 2 ,
     'group_id_vendor' => 4 ,
    'is_unlimited' => 1 ,
    'is_standard' => 0 ,
    'register_fee' => 497 ,
    'templates_pagination_per_page' => 12,
    'custom_templates_pagination_per_page' => 12,
    'media_pagination_per_page' => 6,
    'consumer_group_name'   => 'Consumer',
    'source_template_path'  => 'uploads/sourceTemplates/',
    'custom_template_path'  => 'uploads/customTemplates/',
    'domain_name'           => 'http://personalpages.io/',
    'aweber_app_id'         => '3f69e6d6',
    'grabz_it_application_key'      => 'OGRkZDZiNTE4MmIxNDU4Yzk5NGYyZThhZjk2ZWNlNWU',
    'grabz_it_application_secret'   => 'Pz9YPz8/TD8/P3Y+Pz9rJhY/XT8/Owk/ND9gPxE/JVs=',
   'consumer_pagination_per_page'=> 10,
   'myOrders_per_page'=>10,
   'product_per_page' =>9,
   'AllProduct_per_page' =>10,
   'staff_per_page' =>10,
   'vendor_per_page' =>10,
   'consumer_per_page' =>10,
   'category_per_page' =>10,
   'banner_per_page' =>10,
    'brand_per_page' =>10,
   'user_per_page' =>10,
    'measurement_per_page' =>10,
     'brand_picture_banner_width_size'   => 1200,
    'brand_picture_banner_length_size'  => 278,
    'brand_logo_width_size'             => 200,
    'brand_logo_length_size'            => 200,
    'brand_thumbnail_width_size'        => 150,
    'brand_thumbnail_length_size'       => 100,
     
	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default timezone for your application, which
	| will be used by the PHP date and date-time functions. We have gone
	| ahead and set this to a sensible default for you out of the box.
	|
	*/

	'timezone' => 'UTC',
	//'timezone' => 'America/Toronto',

	/*
	|--------------------------------------------------------------------------
	| Application Locale Configuration
	|--------------------------------------------------------------------------
	|
	| The application locale determines the default locale that will be used
	| by the translation service provider. You are free to set this value
	| to any of the locales which will be supported by the application.
	|
	*/

	'locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Application Fallback Locale
	|--------------------------------------------------------------------------
	|
	| The fallback locale determines the locale to use when the current one
	| is not available. You may change the value to correspond to any of
	| the language folders that are provided through your application.
	|
	*/

	'fallback_locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the Illuminate encrypter service and should be set
	| to a random, 32 character string, otherwise these encrypted strings
	| will not be safe. Please do this before deploying an application!
	|
	*/

	'key' => 'garh9R605BOe73CcPG7iB2XbtCkdPcQP',

	'cipher' => MCRYPT_RIJNDAEL_128,

	/*
	|--------------------------------------------------------------------------
	| Autoloaded Service Providers
	|--------------------------------------------------------------------------
	|
	| The service providers listed here will be automatically loaded on the
	| request to your application. Feel free to add your own services to
	| this array to grant expanded functionality to your applications.
	|
	*/

	'providers' => array(

		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Session\CommandsServiceProvider',
		'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Html\HtmlServiceProvider',
		'Illuminate\Log\LogServiceProvider',
		'Illuminate\Mail\MailServiceProvider',
		'Illuminate\Database\MigrationServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Redis\RedisServiceProvider',
		'Illuminate\Remote\RemoteServiceProvider',
		'Illuminate\Auth\Reminders\ReminderServiceProvider',
		'Illuminate\Database\SeedServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',
        'LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider',
        'LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider',
        'GrahamCampbell\Flysystem\FlysystemServiceProvider',
        'Thujohn\Pdf\PdfServiceProvider',
        'Cartalyst\Sentry\SentryServiceProvider',
        'Laravel\Cashier\CashierServiceProvider',
        'Barryvdh\Cors\CorsServiceProvider',
        'Gloudemans\Shoppingcart\ShoppingcartServiceProvider',
        'Intervention\Image\ImageServiceProvider',
        'Softservlet\Friendship\Laravel\Providers\LaravelFriendshipServiceProvider',
        'Indatus\Dispatcher\ServiceProvider',
        'mnshankar\CSV\CSVServiceProvider',
        'Hugofirth\Mailchimp\MailchimpServiceProvider',
        'Nathanmac\RestClient\RestClientServiceProvider',
        'Mitch\Hashids\HashidsServiceProvider',
        'Bradleyboy\Laravel\BraintreeServiceProvider'
    ),

	/*
	|--------------------------------------------------------------------------
	| Service Provider Manifest
	|--------------------------------------------------------------------------
	|
	| The service provider manifest is used by Laravel to lazy load service
	| providers which are not needed for each request, as well to keep a
	| list of all of the services. Here, you may set its storage spot.
	|
	*/

	'manifest' => storage_path().'/meta',

	/*
	|--------------------------------------------------------------------------
	| Class Aliases
	|--------------------------------------------------------------------------
	|
	| This array of class aliases will be registered when this application
	| is started. However, feel free to register as many as you wish as
	| the aliases are "lazy" loaded so they don't hinder performance.
	|
	*/

	'aliases' => array(

		'App'               => 'Illuminate\Support\Facades\App',
		'Artisan'           => 'Illuminate\Support\Facades\Artisan',
		'Auth'              => 'Illuminate\Support\Facades\Auth',
        'Authorizer'        => 'LucaDegasperi\OAuth2Server\Facades\AuthorizerFacade',
		'Blade'             => 'Illuminate\Support\Facades\Blade',
		'Cache'             => 'Illuminate\Support\Facades\Cache',
        'Carbon'            => 'Carbon\Carbon',
		'ClassLoader'       => 'Illuminate\Support\ClassLoader',
		'Config'            => 'Illuminate\Support\Facades\Config',
		'Controller'        => 'Illuminate\Routing\Controller',
		'Cookie'            => 'Illuminate\Support\Facades\Cookie',
		'Crypt'             => 'Illuminate\Support\Facades\Crypt',
		'DB'                => 'Illuminate\Support\Facades\DB',
		'Eloquent'          => 'Illuminate\Database\Eloquent\Model',
		'Event'             => 'Illuminate\Support\Facades\Event',
		'File'              => 'Illuminate\Support\Facades\File',
		'Form'              => 'Illuminate\Support\Facades\Form',
		'Hash'              => 'Illuminate\Support\Facades\Hash',
		'HTML'              => 'Illuminate\Support\Facades\HTML',
		'Input'             => 'Illuminate\Support\Facades\Input',
		'Lang'              => 'Illuminate\Support\Facades\Lang',
		'Log'               => 'Illuminate\Support\Facades\Log',
		'Mail'              => 'Illuminate\Support\Facades\Mail',
		'Paginator'         => 'Illuminate\Support\Facades\Paginator',
		'Password'          => 'Illuminate\Support\Facades\Password',
		'Queue'             => 'Illuminate\Support\Facades\Queue',
		'Redirect'          => 'Illuminate\Support\Facades\Redirect',
		'Redis'             => 'Illuminate\Support\Facades\Redis',
		'Request'           => 'Illuminate\Support\Facades\Request',
		'Response'          => 'Illuminate\Support\Facades\Response',
		'Route'             => 'Illuminate\Support\Facades\Route',
		'Schema'            => 'Illuminate\Support\Facades\Schema',
		'Seeder'            => 'Illuminate\Database\Seeder',
		'Session'           => 'Illuminate\Support\Facades\Session',
		'SoftDeletingTrait' => 'Illuminate\Database\Eloquent\SoftDeletingTrait',
		'SSH'               => 'Illuminate\Support\Facades\SSH',
		'Str'               => 'Illuminate\Support\Str',
		'URL'               => 'Illuminate\Support\Facades\URL',
		'Validator'         => 'Illuminate\Support\Facades\Validator',
		'View'              => 'Illuminate\Support\Facades\View',
        'Flysystem'         => 'GrahamCampbell\Flysystem\Facades\Flysystem',
        'PDF'               => 'Thujohn\Pdf\PdfFacade',
        'Sentry'            => 'Cartalyst\Sentry\Facades\Laravel\Sentry',
        'Users'             => 'Cartalyst\Sentry\Users\Eloquent\User',
        'Groups'            => 'Cartalyst\Sentry\Groups\Eloquent\Group',
        'Cart'              => 'Gloudemans\Shoppingcart\Facades\Cart',
        'Image'             => 'Intervention\Image\Facades\Image',
        'CSV'               =>'mnshankar\CSV\CSVFacade',
        'MailchimpWrapper'  => 'Hugofirth\Mailchimp\MailchimpWrapper',
        'Hashids'           => 'Mitch\Hashids\Hashids',
        'Process'           => 'Symfony\Component\Process\Process',
        'ProcessFailedException' => 'Symfony\Component\Process\Exception\ProcessFailedException'	),


);
