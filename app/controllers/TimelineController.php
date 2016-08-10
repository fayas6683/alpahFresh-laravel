<?php

class TimelineController extends \BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {


     
     try {
              $page= Input::get('page');
              $system = Input::get('system');
               $processOrder = Input::get('process_order');
               

                    $timeline = Timeline::orderBy('process_order', 'asc');
               
            // return $timeline->get();

                   if($system == '0' || $system == '1')
                 {
               
                $timeline = Timeline::where('system','=',$system);
               
                

                 }
          

              if($processOrder == 'ASC' || $processOrder == 'DESC')
             {
               // trim($dataList, ''');
                 $processOrder = trim($processOrder, "'");
                 $timeline = Timeline::orderBy('process_order', $processOrder);
                 
                 
             }
              

          
               
               if($page){
               $timeline = $timeline->Paginate(Config::get('app.category_per_page'));
              }
            else
            {    
                 $lenght = $timeline->count();
                 $timeline = $timeline->Paginate($lenght);
            }

     return Response::json(array('status' => 'success', 'data' => $timeline->toArray(),'page' => $timeline->paginateObject()), 200);
        
        } Catch (Exception $ex) {
            return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
        }
    
    }


        public function processUpdate()
    {
      try{
                  $processOrders = Input::get('data');
                  $processOrders = explode(',', $processOrders);
                
                            foreach($processOrders as $key=>$value ){
                                   $timeline = Timeline::find($value);
                                  $timeline->process_order = $key+1;
                                  $timeline->save();
                            }
                  return Response::json(array('status' => 'success', 'data' => $processOrders), 200);
                }
                 catch (Exception $ex) {
         return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
        }

    }






























    public function getStatus()

    {
      try{
   // $id = Authorizer::getResourceOwnerId();
    $statusId = Input::get('statusId');


    $status= Timeline::where('id' , '=' , $statusId)->get();
    return Response::json(array('status' => 'success' , 'data' => $status),200);
                
      }
        catch (Exception $ex) {
         return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
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


            $rules = array(
            'status' => 'required',
            'icon' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();
        } else {
            try {
                $timeline            = new Timeline;
                $timeline->status      = Input::get('status');
                $timeline->icon   = Input::get('icon');
                 $timeline->system   = Input::get('system');
                $timeline->save();
               
                return Response::json(array('status' => 'success' , 'category' => $timeline), 200);
            }
            catch (Exception $ex) {
                    return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
            }
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
        $status = Timeline::find($id);
        return $status;
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
            'status' => 'required',
            'icon' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();
        } else {

            try{
            $timeline          = Timeline::find($id);
            $timeline->status    = Input::get('status');
            $timeline->icon = Input::get('icon');
            $timeline->system = Input::get('system');
            $timeline->save();

            return Response::json(array('status' => 'success' , 'category' => $timeline), 200);
        }
        catch (Exception $ex) {
                    return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
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
          
                $timeline = Timeline::find($id);
                $timeline->delete();
                return Response::json(array('status' => 'success' , 'message' => 'Successfully deleted ! ' ),200);
            }
        catch (Exception $ex ){
            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ),500);
        }
    }
}