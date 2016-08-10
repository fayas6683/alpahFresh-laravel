        <?php
        class ProductController extends BaseController //BaseResource
        {
            /**
             * Resource view directory
             * @var string
             */
            protected $resourceView = 'account.product';
            /**
             * Model name of the resource, after initialization to a model instance
             * @var string|Illuminate\Database\Eloquent\Model
             */
            protected $model = 'Product';
            /**
             * Resource identification
             * @var string
             */
            protected $resource = 'myproduct';
            /**
             * Resource database tables
             * @var string
             */
            protected $resourceTable = 'products';
            /**
             * Resource name
             * @var string
             */
            protected $resourceName = 'Goods';
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

                  $search = Input::get('search');
              

                $products = Product::orderBy('created_at','desc');
               
                 

                if($search)
                  {
                     $products = $products->where('name', 'like', "%{$search}%")->orwhere('unit_price', 'like', "%{$search}%");
                  }


                $products = $products->Paginate(Config::get('app.AllProduct_per_page'));
                  return Response::json(array('status' => 'success' , 'data' => $products->toArray(),  'page'=>$products->paginateObject()),200);

            }




            public function makeMainPicture(){
                try {
                    $pic_id = Input::get('main_pic');
                    $productPictures = ProductPictures::find($pic_id);
                    $product_id = $productPictures->product_id;
                    $productPicturesMain = ProductPictures::where('product_id','=',$product_id)->where('main_pic','=',1)->first();
                    $productPictures->main_pic = 1;
                    $productPictures->save();
                    if($productPicturesMain)
                    {
                    $productPicturesMain->main_pic = 0;
                    $productPicturesMain->save();
                }
                    return Response::json(array('status' => 'success', 'main_picture' => $productPictures), 200);
                } catch (Exception $ex) {
                    return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
                }
            }
            public function removeTagProduct()
            {
                $product_id = Input::get('product_id');
                $tag_id = Input::get('tag_id');
                $tagIn = Tags::find($tag_id);
                $tagIn->product()->detach($product_id);
            }
            public function addTagProduct()
            {
                try {
                    $product_id = Input::get('product_id');
                    $tag_text   = Input::get('tag_text');
                    $tagExist = Tags::where('text', 'like', $tag_text)->count();
                    $tagIn = null;
                    $tagMappedToProduct = null;
                    if ($tagExist == 0) {
                        $tagNew = new Tags;
                        $tagNew->text = $tag_text;
                        $tagNew->save();
                        $tagNew->product()->attach($product_id);
                    } else {
                        $tagIn = Tags::where('text', 'like', $tag_text)->first();
                        $tagMappedToProduct = $tagIn->product->find($product_id);
                    }
                    if ($tagExist > 0 && empty($tagMappedToProduct)) {
                        $tagIn->product()->attach($product_id);
                    }
                    return Response::json(array('status' => 'success', 'message' => 'Success'), 200);
                } Catch (Exception $ex) {
                    return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
                }
            }
            public function getAllProducts()
            {
               
                $search = Input::get('search');
               $categoryId = Input::get('categoryId');
               $products = Product::with(array('pictures' => function($query)
               {
                 $query->where('main_pic', '=' ,1);
               }
               ));

               if($categoryId >0)
               {   
              $products = $products->whereHas('category', function($query) use($categoryId)

                {
               $query->where('product_categories_id', '=' ,$categoryId );
                 }
                 );
                
             }


                 if($search) 
             {
              $products = $products->where(function ($query) use($search) {
                
                   $query->orWhere('name', 'like',"%{$search}%" );
                   $query->orWhere('sku_number', 'like',"%{$search}%" );
      
   });
             }
      


           $products = $products->Paginate(Config::get('app.product_per_page'));
                  return Response::json(array('status' => 'success' , 'data' => $products->toArray(),  'page'=>$products->paginateObject()),200);

            }

            public function searchByTags()
            {

               $arrayExistingProductIds = array();
               $arrayProductTags       = array();
               $search = Input::get('search');
                  if($search){
                  $tags = Tags::where('text','like',"%{$search}%")->get();
                  foreach ($tags as $p )
                   {
                    $tempProducts = Tags::with('product')->where('id' ,'=',$p->id)->get();
                     
                     foreach($tempProducts[0]->product as $product)
                     {
                       
                            if (!in_array($product->id, $arrayExistingProductIds )) {

                                $tem  =   Product::with(array('pictures' => function($query)
               {
                 $query->where('main_pic', '=' ,1);
             }
             ))->where("id","=",$product->id)->get();

                      array_push($arrayProductTags,$tem[0]);
                      array_push($arrayExistingProductIds,$product->id);
                        }
                     }    
                  }
              }
                 $currentPage = Input::get('page') - 1;

            $pagePerProduct = Config::get('app.product_per_page');
           $pagedData = array_slice($arrayProductTags, $currentPage * $pagePerProduct, $pagePerProduct );


           return $paginator = Paginator::make($pagedData, count($arrayProductTags), $pagePerProduct );
         
           
            }




            public function getProductByCategory($category_id)
            {
                $products = Product::with('category')->where('category_id', '=', $category_id)->get();
                return $products;
            }
            public function getProductByCategoryWithThumbnail($category_id)
            {
                $products = DB::table('products')
                    ->leftJoin('product_pictures', 'product_pictures.product_id', '=', 'products.id')
                    ->where('category_id', '=', $category_id)
                    ->where('main_pic', '=', 1)
                    ->select(array('products.*', 'product_pictures.thumbnail_location'))
                    ->get();
                return $products;
            }
            /**
             * Resource create view
             * GET         /resource/create
             * @return Response
             */
            public function create()
            {
                if (Auth::user()->alipay == NULL) {
                    return Response::json('Notice: You need to set Alipay account before sale goods', 400);
                } else {
                    $categoryLists = ProductCategories::lists('name', 'id');
                    return $categoryLists;
                }
            }
            /**
             * Resource create action
             * POST        /resource
             * @return Response
             */
            public function store()
            {
                $rules = array(
                    'name'             => 'required|unique:product',
                    'content'           => 'required|min:110',
                    'categories'        => 'required',
                    'tags'              => 'required',
                    'picture'           => 'required',
                    'unit_price'             => 'required',
                    'vendors'        =>'required',
                    'measurement_value' =>'required',
                    'measurement_unit' => 'required'
                );
                $slug = Input::input('name');
                $hashslug = date('H.i.s') . '-' . md5($slug) . '.html';
                $messages = $this->validatorMessages;
                $validator = Validator::make(Input::all(), $rules, $messages);
                if ($validator->fails()) {
                    return Response::json($validator->messages(), 500);
                } else {
                        try {
                            $model                      = new Product;
                            $model->name                = Input::get('name');
                            $model->sku_number          = Input::get('sku_number');
                            $model->minimum_quantity    = Input::get('minimum_quantity');
                            $model->unit_price          = Input::get('unit_price');
                            $model->measurement_value   = Input::get('measurement_value');
                            $model->measurement_unit    = Input::get('measurement_unit');

     if(Input::get('measurement_unit')){


            $measurementUnit = Measurement::find(Input::get('measurement_unit'));
              $model->measurementUnit()->associate($measurementUnit);
          }
                            $model->slug                = $hashslug;
                            $model->description             = Input::get('content');
                   
                            $model->save();
                            $tags               = Input::get('tags');
                            foreach ($tags as $tag) {
                                $tagExist = Tags::where('text', 'like', $tag['text'])->count();
                                $tagIn = null;
                                $tagMappedToProduct = null;
                                if ($tagExist == 0) {
                                    $tagNew = new Tags;
                                    $tagNew->text = $tag['text'];
                                    $tagNew->save();
                                    $tagNew->product()->attach($model->id);
                                } else {
                                    $tagIn = Tags::where('text', '=', $tag['text'])->first();
                                    $tagMappedToProduct = $tagIn->product->find($model->id);
                                }
                                if ($tagExist > 0 && empty($tagMappedToProduct)) {
                                    $tagIn->product()->attach($model->id);
                                }
                            }
                            $categories = Input::get('categories');
                            foreach($categories as $category ){
                                Product::find($model->id)->category()->attach($category['id']);
                            }

                          $vendors = Input::get('vendors');
                            foreach($vendors as $vendor ){
                                Product::find($model->id)->vendor()->attach($vendor['id']);
                            }


                            $foo                              = Input::get('picture');
                            if(!empty($foo)){
                            $destinationPath                  = 'uploads/products/'.$model->name.'_pic.jpeg';
                            $arrayBase64String                = explode(",", $foo );
                            $image                            = base64_decode($arrayBase64String[1]);
                            file_put_contents(public_path($destinationPath),  $image );
                            $picture                          = Image::make(public_path($destinationPath));
                            $picture->fit(530, 430)->save(public_path($destinationPath));
                            $picture->fit(250, 175)->save(public_path('uploads/product_thumbnails/' . $model->name.'_pic.jpeg'));

                                    $models = new ProductPictures;
                                    $models->filename = $destinationPath;
                                    $models->product_id = $model->id;
                                    $models->main_pic = 1;
                                    $models->thumbnail_location = 'uploads/product_thumbnails/' . $model->name.'_pic.jpeg';
                                    $models->save();
                                }
     
                            return Response::json(array('status' => 'success', 'product' => $model), 200);
                     }
                     catch (Exception $ex) {
                        return Response::json(array('status' => 'error', 'error' => $ex->getMessage() , 'line' => $ex->getLine() ), 500);
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
                $data = $this->model->find($id);
                $categoryLists = ProductCategories::lists('name', 'id');
                $product = Product::where('slug', $data->slug)->first();
                $arrayResource = array(
                    'data' => $data,
                    'categoryLists' => $categoryLists,
                    'product' => $product
                );
                return $arrayResource;
            }
            /**
             * Resource edit action
             * PUT/PATCH   /resource/{id}
             * @param  int $id
             * @return Response
             */
            public function update($id)
            {
                $data = Input::all();
                $rules = array(
                    'name'         => 'required',
                    'description'       => 'required|min:110',
                    'unit_price'         => 'required',
                );





                $messages = $this->validatorMessages;
                $validator = Validator::make($data, $rules, $messages);
                if ($validator->passes()) {
                    try {
                        $model                  = Product::find($id);
                        $model->name           = $data['name'];
                         $model->sku_number  = $data['sku_number'];
                          $model->minimum_quantity  =  $data['minimum_quantity'];

                        $model->unit_price           = $data['unit_price'];
                        $model->description         = $data['description'];
         $model->measurement_value              = $data['measurement_value'];
            $model->measurement_unit              =$data['measurement_unit'];
             
         if($data['measurement_unit']){

            $measurementUnit = Measurement::find($data['measurement_unit']);
              $model->measurementUnit()->associate($measurementUnit);

}


                        $foo                                  = Input::get('picture');
                        if(!empty($foo)){
                            $destinationPath                  = 'uploads/products/'.$model->name.'_pic.jpeg';
                            $arrayBase64String                = explode(",", $foo );
                            $image                            = base64_decode($arrayBase64String[1]);
                            file_put_contents(public_path($destinationPath),  $image );
                            $picture                          = Image::make(public_path($destinationPath));
                            $picture->fit(530, 460)->save(public_path($destinationPath));
                            $picture->fit(250, 175)->save(public_path('uploads/product_thumbnails/' . $model->name.'_pic.jpeg'));
      
                            $models = new ProductPictures;
                            $models->filename = $destinationPath;
                            $models->product_id = $model->id;
                            $models->main_pic = 1;
                            $models->thumbnail_location = 'uploads/product_thumbnails/' . $model->name.'_pic.jpeg';
                            $models->save();
     
                        }
           
                        $existingCategories = $model->category;
                        foreach($existingCategories as $category ){
                            Product::find($model->id)->category()->detach($category['id']);
                        }
                        $categories = Input::get('category');
                        foreach($categories as $category ){
                            Product::find($model->id)->category()->attach($category['id']);
                        }
                        $model->save();
                        return Response::json(array('status' => 'success' , 'message' => 'Successfully updated' ),200);
                    }
                    catch (Exception $ex ){
                            return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ),500);
                    }
                } else {
                    return Response::json($validator->messages(), 500);
                }
            }
            /**
             * Resource destory action
             * DELETE      /resource/{id}
             * @param  int $id
             * @return Response
             */
            public function destroy($id)
            {
                try {
                    
                        $product = Product::find($id);
                        $product->delete();
                        return Response::json(array('status' => 'success' , 'message' => 'Successfully deleted ! ' ),200);
                    }
                catch (Exception $ex ){
                    return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ),500);
                }
            }
            public function uploadImage()
            {
                try {
                    $input = Input::all();
                    $rules = array(
                        //            'file' => 'image|max:3000',
                    );
                    $validation = Validator::make($input, $rules);
                    if ($validation->fails()) {
                        return Response::make($validation->errors->first(), 400);
                    }
                    $file = Input::file('file');
                    $destinationPath = 'uploads/products/';
                    $ext = $file->guessClientExtension();
                    $fullname = $file->getClientOriginalName();
                    $hashname = date('H.i.s') . '-' . md5($fullname) . '.' . $ext;
                    $picture = Image::make($file->getRealPath());
                    $picture->fit(530, 440)->save(public_path($destinationPath . $hashname));
                    $picture->fit(250, 175)->save(public_path('uploads/product_thumbnails/' . $hashname));
                    return Response::json(array('status' => 'success', 'picture' => 'upload successful'), 200);
                } catch (Exception $ex) {
                    return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
                }
            }

            /**
             * Action: Add resource images
             * @return Response
             */
            public function postUpload($product_id)
            {
                try {
                    $input = Input::all();
                    $rules = array(
                            //            'file' => 'image|max:3000',
                    );
                    $validation = Validator::make($input, $rules);
                    if ($validation->fails()) {
                        return Response::make($validation->errors->first(), 400);
                    }
                    $file = Input::file('file');
                    $id = $product_id;
                    $destinationPath = 'uploads/products/';
                    $ext = $file->guessClientExtension();
                    $fullname = $file->getClientOriginalName();
                    $hashname = date('H.i.s') . '-' . md5($fullname) . '.' . $ext;
                    $picture = Image::make($file->getRealPath());
                    $picture->fit(530, 440)->save(public_path($destinationPath . $hashname));
                    $picture->fit(250, 175)->save(public_path('uploads/product_thumbnails/' . $hashname));
      
                        $models = new ProductPictures;
                        $models->filename = $destinationPath . $hashname;
                        $models->product_id = $id;
                      $models->main_pic = 0;
                        $models->thumbnail_location = 'uploads/product_thumbnails/' . $hashname;
                        $models->save();
      
                    return Response::json(array('status' => 'success', 'picture' => $models), 200);
                } catch (Exception $ex) {
                    return Response::json(array('status' => 'error', 'error' => $ex->getMessage(), 'line' => $ex->getLine()), 500);
                }
            }
            /**
             * Action: Delete resource images
             * @return Response
             */
            public function deleteImage()
            {
               
                $id       = Input::get('id');
                $filename = ProductPictures::find($id); // ->where('user_id', Auth::user()->id)
                $oldImage = $filename->filename;
                $oldImageThumbnail = $filename->thumbnail_location;
                if (is_null($filename))
                    return Redirect::back()->with('error', 'Can\'t find picture');
                elseif (!empty($filename)) {
                    File::delete(
                        public_path($oldImage)
                    );
                    File::delete(
                        public_path($oldImageThumbnail)
                    );
                    $filename->delete();
                    return Response::json(array('status' => 'success', 'message' => 'Delete success'), 200);
                } else{
                    return Response::json(array('status' => 'warning', 'message' => 'Delete fail'), 500);
                }
            }
            /**
             * View: My comments
             * @return Response
             */
 
            /**
             * Action: Delete my comments
             * @return Response
             */

            /**
             * View: Product
             * @return Respanse
             */

            /**
             * Resource list
             * @return Respanse
             */

            public function getProductById()
            {
               $productId = Input::get('productId');
               $product = Product::with(array('pictures' => function($query)
                                {
                                   $query->where('main_pic', '=' ,1);
                                }
                                ))->with('tags','measurementUnit')->where('id' ,'=', $productId)->first();
              return $product;
            }
            /**
             * Resource show view
             * @param  string $slug Slug
             * @return response
             */
            public function show($id)
            {
                $product = Product::where('id','=', $id)->with('category','vendor','pictures','tags')->first();
                return $product;
            }
            // ...
        }