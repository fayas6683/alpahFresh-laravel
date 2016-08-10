<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 9/10/15
 * Time: 1:52 PM
 */


class MeasurementController extends \BaseController
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
               $measurement = Measurement::orderBy('created_at', 'desc');
               if($page){
               $measurement = $measurement->Paginate(Config::get('app.measurement_per_page'));
              }
            else
            {    
                 $lenght = $measurement->count();
                 $measurement = $measurement->Paginate($lenght);
            }

     return Response::json(array('status' => 'success', 'data' => $measurement->toArray(),'page' => $measurement->paginateObject()), 200);

        } Catch (Exception $ex) {
            return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
  

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(

            'data' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {

            $tag = new Tags;

            $tag->name = Input::get('name');
            $tag->save();

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
        $tag = Tags::find($id);
        return $tag;
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

            'name' => 'required'

        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();

        } else {

            $tag = Tags::find($id);

            $tag->name = Input::get('name');
            $tag->save();

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
        $tag = Tags::find($id);
        $tag->delete();
    }


}
