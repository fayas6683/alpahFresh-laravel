<?php
/**
 * Created by PhpStorm.
 * User: tonytlucas
 * Date: 4/15/15
 * Time: 9:43 AM
 *
 *
 */
class BrandController extends \BaseController
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
               $brands = Brand::orderBy('created_at', 'desc');
               if($page){
               $brands = $brands->Paginate(Config::get('app.brand_per_page'));
              }
            else
            {    
                 $lenght = $brands->count();
                 $brands = $brands->Paginate($lenght);
            }

     return Response::json(array('status' => 'success', 'data' => $brands->toArray(),'page' => $brands->paginateObject()), 200);

        } Catch (Exception $ex) {
            return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
        }
    }

    
    public function getbrands()
    {
        return $brand = Brand::paginate(5);

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
                $brand            = new Brand;
                $brand->name      = Input::get('name');
                 $brand->content      = Input::get('content');
                $brand->save();
                $destinationPath    = 'uploads/brands/'.md5($brand->name.time()).'_brand_tile_image.png';
                $thumbnaildestinationPath = 'uploads/brand_thumbnails/'.$brand->name.'_brand_thumbnail.png';
                $foo                = Input::get('tile_image');
                if(!empty($foo)){
                    $arrayBase64String      = explode(",", $foo );
                    $image                  = base64_decode($arrayBase64String[1]);
                    file_put_contents(public_path($destinationPath),  $image );
                    $pictureTile            = Image::make(public_path($destinationPath));
                     $pictureThumbnail       = Image::make(public_path($destinationPath));
                    $pictureTile->fit(1200,360)->save(public_path($destinationPath));

                    $pictureThumbnail->fit(Config::get('app.brand_thumbnail_width_size'),Config::get('app.brand_thumbnail_length_size'))->save(public_path($thumbnaildestinationPath));
                    $brand->thumbnails       = $thumbnaildestinationPath;
                    $brand->tile_image       = $destinationPath;
                    $brand->save();


                }


    
                return Response::json(array('status' => 'success' , 'data' => $brand), 200);
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
        $brands = Brand::find($id);
        return $brands;
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
            $brand          = Brand::find($id);
            $brand->name    = Input::get('name');
             $brand->content      = Input::get('content');
            $brand->save();
                  $destinationPath    = 'uploads/brands/'.md5($brand->name.time()).'_brand_tile_image.png';
                $thumbnaildestinationPath = 'uploads/brand_thumbnails/'.$brand->name.'_brand_thumbnail.png';
            $foo                        = Input::get('tile_image');
            if(!empty($foo)){
                     $arrayBase64String      = explode(",", $foo );
                    $image                  = base64_decode($arrayBase64String[1]);
                    file_put_contents(public_path($destinationPath),  $image );
                    $pictureTile            = Image::make(public_path($destinationPath));
                     $pictureThumbnail       = Image::make(public_path($destinationPath));
                    $pictureTile->fit(1200,360)->save(public_path($destinationPath));

                    $pictureThumbnail->fit(Config::get('app.brand_thumbnail_width_size'),Config::get('app.brand_thumbnail_length_size'))->save(public_path($thumbnaildestinationPath));
                    $brand->thumbnails       = $thumbnaildestinationPath;
                    $brand->tile_image       = $destinationPath;
                    $brand->save();
            }
            $brand->save();
            return Response::json(array('status' => 'success' , 'data' => $brand), 200);
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
          
                $brand = Brand::find($id);
                $brand->delete();
                return Response::json(array('status' => 'success' , 'message' => 'Successfully deleted ! ' ),200);
            
        }catch (Exception $ex ){
            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ),500);
        }
    }
}