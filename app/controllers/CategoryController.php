<?php

class CategoryController extends \BaseController
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
               $categories = ProductCategories::orderBy('created_at', 'desc');
               if($page){
               $categories = $categories->Paginate(Config::get('app.category_per_page'));
              }
            else
            {    
                 $lenght = $categories->count();
                 $categories = $categories->Paginate($lenght);
            }

     return Response::json(array('status' => 'success', 'data' => $categories->toArray(),'page' => $categories->paginateObject()), 200);

        } Catch (Exception $ex) {
            return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
        }
    }
    public function getAllCategories(){
        return $categories = ProductCategories::all();
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
            'name' => 'required',
            'content' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();
        } else {
            try {
                $productCategory            = new ProductCategories;
                $productCategory->name      = Input::get('name');
                $productCategory->content   = Input::get('content');
                $productCategory->save();
               
                return Response::json(array('status' => 'success' , 'category' => $productCategory), 200);
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
        $productCategory = ProductCategories::find($id);
        return $productCategory;
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
            'name' => 'required',
            'content' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();
        } else {

            try{
            $productCategory          = ProductCategories::find($id);
            $productCategory->name    = Input::get('name');
            $productCategory->content = Input::get('content');
            $productCategory->save();

            return Response::json(array('status' => 'success' , 'category' => $productCategory), 200);
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
          
                $productCategory = ProductCategories::find($id);
                $productCategory->delete();
                return Response::json(array('status' => 'success' , 'message' => 'Successfully deleted ! ' ),200);
            }
        catch (Exception $ex ){
            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ),500);
        }
    }
}