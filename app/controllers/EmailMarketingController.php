<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 11/2/15
 * Time: 5:39 PM
 */

class EmailMarketingController  extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {

            $emailMarketing = EmailMarketing::where('campaign_type', '=', 'email_marketing')->get();


            return Response::json(array('status' => 'success' , 'template' => $emailMarketing ),200);

        }catch (Exception $ex ){

            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage(), 'line' => $ex->getLine() ),500 );
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

            'name'                => 'required',
            'directory'           => 'required',

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {

            try {


                $emailMarketing               = new EmailMarketing;

                $emailMarketing->name         = Input::get('name');
                $emailMarketing->directory    = Input::get('directory');
                $emailMarketing->save();


                return Response::json(array('status' => 'success' , 'template' => $emailMarketing ),200);

            }catch (Exception $ex ){

                return Response::json(array('status' => 'error' , 'error' => $ex->getMessage(), 'line' => $ex->getLine() ),500 );
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $emailMarketing = EmailMarketing::find($id);
        return $emailMarketing->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $rules = array(

            'name'                => 'required',
            'directory'           => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            try {

                $emailMarketing             = EmailMarketing::find($id);

                $emailMarketing->name         = Input::get('name');
                $emailMarketing->directory    = Input::get('directory');
                $emailMarketing->save();


                return Response::json(array('status' => 'success' , 'template' => $emailMarketing ),200);

            }catch (Exception $ex ){

                return Response::json(array('status' => 'error' , 'error' => $ex->getMessage(), 'line' => $ex->getLine() ),500 );
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $emailMarketing = EmailMarketing::find($id);
        $emailMarketing->delete();
    }

} 