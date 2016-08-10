<?php
/**
 * Created by PhpStorm.
 * User: tonytlucas
 * Date: 4/15/15
 * Time: 9:43 AM
 *
 *
 */
class BannerController extends \BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       
        try {
               $banners = Banner::orderBy('created_at', 'desc');
               $banners = $banners->Paginate(Config::get('app.banner_per_page'));
               return Response::json(array('status' => 'success', 'data' => $banners->toArray(),'page' => $banners->paginateObject()), 200);
        } Catch (Exception $ex) {
            return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
        }
    }

    
    public function getBanners()
    {
        return $banner = Banner::paginate(5);

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
            'name' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json($validator->messages(), 500); //$validator->messages()->toJson();
        } else {
            try {
                $banner            = new Banner;
                $banner->name      = Input::get('name');
                $banner->save();
                $destinationPath    = 'uploads/banners/'.md5($banner->name.time()).'_banner_tile_image.png';
                $foo                = Input::get('tile_image');
                if(!empty($foo)){
                    $arrayBase64String      = explode(",", $foo );
                    $image                  = base64_decode($arrayBase64String[1]);
                    file_put_contents(public_path($destinationPath),  $image );
                    $pictureTile            = Image::make(public_path($destinationPath));
                    $pictureTile->fit(1200,360)->save(public_path($destinationPath));
                    $banner->tile_image       = $destinationPath;
                    $banner->save();
                }
                return Response::json(array('status' => 'success' , 'data' => $banner), 200);
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
        $banners = Banner::find($id);
        return $banners;
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
            $banner          = Banner::find($id);
            $banner->name    = Input::get('name');
            $banner->save();

            $foo                        = Input::get('tile_image');
            if(!empty($foo)){
                $destinationPath        = 'uploads/banners/'.md5($banner->name.time()).'_banner_tile_image.png';
                $arrayBase64String      = explode(",", $foo );
                $image                  = base64_decode($arrayBase64String[1]);
                file_put_contents(public_path($destinationPath),  $image );
                $pictureTile            = Image::make(public_path($destinationPath));
                $pictureTile->fit(1200,360)->save(public_path($destinationPath));
                $banner->tile_image       = $destinationPath;
            }
            $banner->save();
            return Response::json(array('status' => 'success' , 'data' => $banner), 200);
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
          
                $banner = Banner::find($id);
                $banner->delete();
                return Response::json(array('status' => 'success' , 'message' => 'Successfully deleted ! ' ),200);
            
        }catch (Exception $ex ){
            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ),500);
        }
    }
}