<?php
/**
 * Created by PhpStorm.
 * User: tonytlucas
 * Date: 11/3/15
 * Time: 12:03 PM
 */
class OrderController extends BaseController
{
    /**
     * Model name of the resource, after initialization to a model instance
     * @var string|Illuminate\Database\Eloquent\Model
     */
    protected $model = 'Order';
    /**
     * Resource identification
     * @var string
     */
    protected $resource = 'myorder';
    /**
     * Resource database tables
     * @var string
     */
    protected $resourceTable = 'order';
    /**
     * Custom validation message
     * @var array
     */
    protected $validatorMessages = array(
        'title.required' => 'Please fill goods name',
        'price.required' => 'Please fill goods price',
        'price.numeric' => 'Price only be a number',
        'quantity.required' => 'Please fill remaining quantity of goods',
        'quantity.integer' => 'Remaining quantity of goods must be an integer',
        'province.required' => 'Please select province and city',
        'content.required' => 'Please fill content',
        'category.exists' => 'Please choose goods category',
    );
    /**
     * Resource list view
     * GET         /resource
     * @return Response
     */
    public function index()
    {

          try {

             //  return Order::with('timeline')->get();
                 $status= Input::get('status');
                 $search = Input::get('search');
                 $id = Authorizer::getResourceOwnerId();
                 $orders= Order::where('status','=',$status)->with('user');

              if($search){
               $orders = $orders->whereHas('user', function($query) use($search)

                {
                   $query->where('username', 'like', "%{$search}%" ); 
                }
                 )->orWhere('invoice_id', 'like',"%{$search}%" )->orWhere('amount', 'like',"%{$search}%" )->orWhere('created_at', 'like',"%{$search}%" );
               }
             $orders = $orders->with('staff','timeline')->orderBy('delivery_date','asc');
           
                 
                $orders = $orders->Paginate(Config::get('app.vendor_per_page'));
                  return Response::json(array('status' => 'success' , 'data' => $orders->toArray(),  'page'=>$orders->paginateObject()),200);
             
        } catch (Exception $ex) {
            return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
        }
        
    }
    public function timelinedetail()

    {
      try{
   // $id = Authorizer::getResourceOwnerId();
    $hashId = Input::get('hashId');
    $orderTimelines  = Order::where('hash_id' , '=' , $hashId)->first()->timeline()->orderBy('pivot_created_at','ASC')->get();

 $order = Order::where('hash_id' , '=' , $hashId)->first();
 $data = array(
     'order' => $order,
     'timeline' => $orderTimelines );

       

    return Response::json(array('status' => 'success' , 'data' => $data),200);      
      }
        catch (Exception $ex) {
         return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
        }
    }


   public function currentTimeline()
   {
    try{
      $orderId= Input::get('orderId');
      $order = Order::find($orderId);


     // $timeLines = Order::where('id','=',  $orderId)->with(['timeline' => function($q) { $q->orderBy('created_at','ASC');}])->get();

   
  $timeLines = Order::where('id','=',  $orderId)->first()->timeline()->orderBy('pivot_created_at','DESC')->first();


  //$timeLines = Order::where('id','=',  $orderId)->with('timeline')->get();
       return Response::json(array('status' => 'success' , 'data' => $timeLines),200);
       }
        catch (Exception $ex) {
         return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
        }
   }


   public function updateStatus()
   {

               try{
                 $orderId= Input::get('orderId');
                 $statusId = Input::get('statusId');
                 $message = Input::get('message');
                 $order = Order::find($orderId);
                 $status = Timeline::where('id','=',$statusId)->first();
                 $invoiceId = $order->invoice_id;
                 $amount = $order->amount;
                 $currentStatus = $status->status;

               $userInfo = Order::where('id' ,'=',$orderId)->with('user')->first();
              $user =$userInfo->user->username;


              $checkstatus = $order->whereHas('timeline', function($query) use($statusId){
                   $query->where('timeline_id', '=' ,$statusId );
                 })->get();
                $lenght = $checkstatus->count();
                  if($lenght===0)
                  {
                  $order->timeline()->attach($statusId, array("status_message"=>$message));
                  }else
                  { 
                    $order->timeline()->detach($statusId);
                  //  $timeline = $order->timeline()->first(); //Timeline::find($statusId);
                 //   $order->timeline()->save( $timeline, ['status_message' =>'kkgfgk']);
                   $order->timeline()->attach($statusId, array("status_message"=>$message));
                   }

                          $array = array(
       'link' =>Config::get('app.domain_name').'#/feedback/'.$order->hash_id,
       'order'  =>$order,
       'status' =>$status,
        'email'  => Config::get('app.sender_info')

      );

 sendStatusUpdate($user, 'Status Update', $array);
 return Response::json(array('status' => 'success' , 'data' =>$order),200);

                 
                }
                 catch (Exception $ex) {
         return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
        }

   }



  public function getAllOrderByConsumer()
    {
              try {
                $search = Input::get('search');

                 $id = Authorizer::getResourceOwnerId();
                 $orders = Order::where('user_id' , '=' , $id)->orderBy('created_at', 'DESC');
                 if($search)
                 {
                    $orders = $orders->where('created_at', 'like', "%{$search}%")->orwhere('amount', 'like', "%{$search}%")->orwhere('invoice_id', 'like', "%{$search}%");
                 }
                  $orders = $orders->Paginate(Config::get('app.myOrders_per_page'));
                  return Response::json(array('status' => 'success' , 'data' => $orders->toArray(),  'page'=>$orders->paginateObject()),200);

          
        } catch (Exception $ex) {
            return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
        }
    }

  
   public function getItemsByOrder()
   {
   
     try{
             $orderId = Input::get('orderId');
             $order    = Order::where('id','=',$orderId)
                          ->with('user');
             $order = $order->with(array('orderReceipt' => function($query)
               {
                 $query->with('product');
               }
               ))->first();
         return    Response::json(array('status' => 'success' , 'data' =>      $order ));
         } 
     catch(Exception $ex){
        return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
     }

   }
   

    /**
     * Resource create view
     * GET         /resource/create
     * @return Response
     */
    public function create()
    {
    }
    /**
     * Resource create action
     * POST        /resource
     * @return Response
     */
    public function store()
    {

        $rules = array(
            'amount' => 'required',
            'items'   => 'required',
            'delivery_date' =>'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json($validator->messages(), 500);
        } else {
         try{
                 
               
                  $amount = Input::get('amount');
                   $delivery_date = Input::get('delivery_date');
                  $id       = Authorizer::getResourceOwnerId();
                  $user    = Cartalyst\Sentry\Users\Eloquent\User::find($id);
                  $nonceFromTheClient = Input::get('nonce');
                  $transaction = Braintree_Transaction::sale([
                                      'amount' => $amount,
                                      'paymentMethodNonce' => $nonceFromTheClient,
                                      'options' => [
                                        'submitForSettlement' => True
                                      ]
                                    ]);
            
                   if($transaction->transaction)
                   {
                  $chargeId =  $transaction->transaction->id;
                }
                else
                {
                      return Response::json(array('status' => 'error', 'data' => $transaction),500);                                                                                                                                                                                                                                               
                }
                  if($chargeId)
                  {
                      $order   = new Order;
                    $order->charge_id = $chargeId;
                  $order->user()->associate($user);
                  $order->status = 0;
                  $order->delivery_date = $delivery_date;
                  $order->amount = $amount;
                  $order->invoice_id = md5(time() . $id);
                  $order->save();

                  $order->hash_id = Hashids::encode($order->id);
                  $order->save();


                    $arrayData = Input::get('items');
                    $arrayProducts = array();
                       foreach($arrayData as $item )
                        {

                        $orderReceipt = new OrderReceipt;
                        $product = Product::find($item['code']);
                        $orderReceipt->product()->associate($product);
                        $orderReceipt->order()->associate($order);
                        $orderReceipt->quantity = $item['quantity'];
                        $orderReceipt->save();

                         }
                          $order->timeline()->attach(1);
                           $order->timeline()->attach(2);
               return Response::json(array('status' => 'success' , 'data' => $order));
                  }
                

             }
             catch (Exception $ex) {
            return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
           }
       }
    }
    /**
     * Resource edit view
     * GET         /resource/{id}/edit
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
    }
    /**
     * Resource edit action
     * PUT/PATCH   /resource/{id}
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $rules = array(
            'status' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json($validator->messages(), 500);
        } else {
            try {
                  $staffId               = Authorizer::getResourceOwnerId();
                  $order                  = Order::find($id);
                  $order->status        = Input::get('status');
                  $staff = Cartalyst\Sentry\Users\Eloquent\User::find($staffId);
                  $order->staff()->associate($staff);

                 
                   $order->timeline()->attach(12, array("status_message"=>"Order Shipped"));
                  $order->save();
                return Response::json(array('status' => 'success', 'pledge' => $order), 200);
            } Catch (Exception $ex) {
                return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
            }
        }
    }
    /**
     * Resource destory action
     * DELETE      /resource/{id}
     * @param  int $id
     * @return Response
     */

   public function addFeedback()
   {
         $rules = array(
            'rating'   => 'required',
            'is_rated'   => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json($validator->messages(), 500);
        } else {
          try{
            $id = Input::get('id');
            $order = Order::find($id);
            $feedback = Input::get('feedback');
            $rating = Input::get('rating');
            $isRated = Input::get('is_rated');
          
            $order->feedback = $feedback;
            $order->rating = $rating;
            $order->is_rated = $isRated;
            $order->save();


             return Response::json(array('status' => 'success', 'pledge' => $order), 200);
              }
               Catch (Exception $ex) {
                return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
            }

          }

   }

    public function destroy($id)
    {
            
                try {
                    
              $order = Order::find($id);
              $transactionId = $order->charge_id;
               $result = Braintree_Transaction::refund($transactionId);
            
             if(!$result->success)
                         {
        return Response::json(array('status' => 'error' , 'error' => $result->message ),500);
                      }
                       else
                       {
                       $order->delete();
                          
                       }
                        return Response::json(array('status' => 'success' , 'message' => 'Successfully deleted ! ' ),200);
                    }
                catch (Exception $ex ){
                    return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ),500);
                }


    }
    /**
     * Resource show view
     * @param  string $slug Slug
     * @return response
     */
    public function show($id)
    {
        $order = Order::where('id' ,'=',$id)->with('timeline')->first();
        return $order;
    }
}

function sendStatusUpdate($email, $subject, $array)
    {

        $emailRecipients = array('email' => $email, 'first_name' => Config::get('app.sender_info') , 'from' => Config::get('app.sender_info') , 'from_name' => 'Silco', 'subject' => $subject);

        Mail::send('emails.statusUpdate', $array, function ($message) use ($emailRecipients) {
            $message->from($emailRecipients['from'], $emailRecipients['from_name']);

            $message->to($emailRecipients['email'], $emailRecipients['first_name'])->subject($emailRecipients['subject']);
        });

    }
