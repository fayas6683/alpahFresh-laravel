<?php

/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 2/18/15
 * Time: 2:27 PM
 */

//Stripe::setApiKey('sk_test_OkiTAQeCfaucYe9EML0nFTRf');

class MenuController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */


    public function index()
    {
        $menus = Menu::all();
        return $menus;
    }

    public function getMenuByUser()
    {
        try{
        $id     = Authorizer::getResourceOwnerId();
        $user   = \Cartalyst\Sentry\Users\Eloquent\User::where('id','=',$id)->with('groups')->first();
        $groupId  = $user->groups[0]->id ;
        if($groupId){
             $menues = Groups::where('id','=',$groupId )
                                ->with(array('menu' => function($query)
                                   {
                                     $query ->orderBy('order_number','asc');
                                   }
                                   ))
                                ->remember(60)
                                ->first();
           return    Response::json(array('status' => 'success' , 'data' =>      $menues ));
        }
     } 
     catch(Exception $ex){
        return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
     }
       
    }

    public function addGroupMenu()
    {

        $group_id = Input::get('group_id');
        $menu_id = Input::get('menu_id');

        $groups = Groups::find($group_id);
        $groups->menu()->attach($menu_id);

    }
    public function removeGroupMenu()
    {

        $group_id = Input::get('group_id');
        $menu_id = Input::get('menu_id');

        $groups = Groups::find($group_id);
        $groups->menu()->detach($menu_id);

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
            'icon' => 'required',
            'view_name' => 'required',
            'order_number' => 'required',
            'parent_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $menu = new Menu;


            $menu->name = Input::get('name');
            $menu->icon = Input::get('icon');
            $menu->view_name = Input::get('view_name');
            $menu->order_number = Input::get('order_number');
            $menu->parent_id = Input::get('parent_id');

            $menu->save();
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
        $symbols = Menu::find($id);
        return $symbols;
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
            'icon' => 'required',
            //'view_name' => 'required',
            'order_number' => 'required'
            //'parent_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            return Response::json($validator->messages(), 500);

        } else {
            $menu = Menu::find($id);


            $menu->name = Input::get('name');
            $menu->icon = Input::get('icon');
            $menu->view_name = Input::get('view_name');
            $menu->order_number = Input::get('order_number');
            $menu->parent_id = Input::get('parent_id');

            $menu->save();
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
        //
    }


}
