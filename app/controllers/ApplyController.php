<?php

/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 4/10/15
 * Time: 9:42 AM
 */
class ApplyController extends \BaseController
{


    public function uploadImageFromEditor()
    {
        try {


            $allowedExts = array("gif", "jpeg", "jpg", "png");

            // Get filename.
            $temp = explode(".", $_FILES["file"]["name"]);

            // Get extension.
            $extension = end($temp);

            // An image check is being done in the editor but it is best to
            // check that again on the server side.
            // Do not use $_FILES["file"]["type"] as it can be easily forged.
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);

            if ((($mime == "image/gif")
                    || ($mime == "image/jpeg")
                    || ($mime == "image/pjpeg")
                    || ($mime == "image/x-png")
                    || ($mime == "image/png"))
                && in_array(strtolower($extension), $allowedExts)) {
                // Generate new random name.
                $name = sha1(microtime()) . "." . $extension;

                // Save file in the uploads folder.
                move_uploaded_file($_FILES["file"]["tmp_name"], getcwd() . "/uploads/" . $name);

                // Generate response.
                $response = new StdClass;
                $response->link = "/uploads/" . $name;

                return  stripslashes(json_encode($response));
            }



            }catch (Exception $ex ){

                return Response::json(array('status' => 'error' , 'error' => $ex->getMessage() ,'line' => $ex->getLine() ),500);
            }

    }


    public function upload()
    {


        $rules = array(

            'file' => 'required', // 'image|max:3000',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {


            return Response::json(array("error" => "Failed: upload" . $validator->messages()));

        } else {


            if (Input::file('file')->isValid()) {


                $file                   = Input::file('file');
                $destinationPath        = 'uploads/media_images/';
                $ext                    = $file->guessClientExtension();
                $fullname               = $file->getClientOriginalName();
                $hashname               = date('H.i.s') . '-' . md5($fullname) . '.' . $ext;
                $picture                = Image::make($file->getRealPath());

                $picture->fit(1024, 683)->save(public_path($destinationPath . $hashname));


                $userId                 = Authorizer::getResourceOwnerId();
                $user                   = \Cartalyst\Sentry\Users\Eloquent\User::find($userId);

                $Media                  = new Media;

                $Media->name            = Input::get('name');
                $Media->user()->assocaute($user);
                $Media->save();


                return Response::json(array("status" => "successful"), 200);

            } else {

                // sending back with error message.
                return Response::json(array("status" => "fail"), 400);

            }
        }
    }


    public function uploadKeyFacts()
    {

        // getting all of the post data

        // setting up rules
        $rules = array(
            'file' => 'required', 'image|max:3000',
            'description' => 'required'
        );

        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            // send back to the page with the input data and errors

            return Response::json(array("error" => "Failed: upload" . $validator->messages()));

        } else {
            // checking file is valid.

            if (Input::file('file')->isValid()) {


                $file = Input::file('file');
                $destinationPath = public_path('uploads/KeyFacts');
                $ext = $file->getClientOriginalExtension(); // Get real extension according to mime type

                if (strcmp($ext, 'pdf')) {

                    return Response::json(array("status" => "fail"), 502);

                } else {

                    $fullname = $file->getClientOriginalName(); // Client file name, including the extension of the client
                    $hashname = date('H.i.s') . '-' . md5($fullname) . '.' . $ext; // Hash processed file name, including the real extension

                    $nutritionalResource = new NutritionResource;
                    $nutritionalResource->resource_type = 2;
                    $nutritionalResource->description = Input::get('description');
                    $nutritionalResource->link = $hashname;
                    $nutritionalResource->save();

                    $allProfessionals = DB::table('users_groups')
                        ->where('users_groups.group_id','=',1)
                        ->select('users_groups.user_id')
                        ->get();

                    foreach($allProfessionals as $item ){

                        $usersSelectedResource = new UsersSelectedResource;

                        $user = \Cartalyst\Sentry\Users\Eloquent\User::find($item->user_id);

                        $usersSelectedResource->users()->associate($user);
                        $usersSelectedResource->NutritionResource()->associate($nutritionalResource);
                        $usersSelectedResource->status = 0;
                        $usersSelectedResource->save();
                    }

                    $upload_success = $file->move($destinationPath, $hashname);

                    return Response::json(array("status" => "successful", 'response' => 'success'), 200);
                }


            } else {

                // sending back with error message.
                return Response::json(array("status" => "fail"), 400);

            }
        }
    }

    public function uploadVideos()
    {

        // getting all of the post data

        // setting up rules
        $rules = array(
            'file' => 'required', // 'image|max:3000',
            'description' => 'required',
            'exclusive'   => 'required',
            'resource_category_id' => 'required'
        );

        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            // send back to the page with the input data and errors

            return Response::json(array("error" => "Failed: upload" . $validator->messages()));

        } else {
            // checking file is valid.

            if (Input::file('file')->isValid()) {


                $file = Input::file('file');
                $destinationPath = public_path('uploads/Videos');
                $ext = $file->getClientOriginalExtension(); // Get real extension according to mime type
                $fullname = $file->getClientOriginalName(); // Client file name, including the extension of the client

                if (strcmp($ext,'mp4')) {

                    return Response::json(array("status" => "fail"), 502);

                } else {
                    $hashname = date('H.i.s') . '-' . md5($fullname) . '.' . $ext; // Hash processed file name, including the real extension

                    $resourceCategoryId = Input::get('resource_category_id');
                    $nutritionalCategory = ResourceCategory::find($resourceCategoryId);

                    $nutritionalResource = new NutritionResource;
                    $nutritionalResource->resource_type = 1;
                    $nutritionalResource->description   = Input::get('description');
                    $nutritionalResource->exclusive     = Input::get('exclusive');
                    $nutritionalResource->resourceCategory()->associate($nutritionalCategory);
                    $nutritionalResource->link = $hashname;
                    $nutritionalResource->save();


                    $allProfessionals = DB::table('users_groups')
                                        ->where('users_groups.group_id','=',1)
                                        ->select('users_groups.user_id')
                                        ->get();

                    foreach($allProfessionals as $item ){

                        $usersSelectedResource = new UsersSelectedResource;

                        $user = \Cartalyst\Sentry\Users\Eloquent\User::find($item->user_id);

                        $usersSelectedResource->users()->associate($user);
                        $usersSelectedResource->NutritionResource()->associate($nutritionalResource);
                        $usersSelectedResource->status = 0;
                        $usersSelectedResource->save();
                    }

                    $upload_success = $file->move($destinationPath, $hashname);

                    return Response::json(array("status" => "successful", 'response' => $ext), 200);
                }
            } else {

                // sending back with error message.
                return Response::json(array("status" => "fail"), 400);

            }
        }
    }

    public function show($id)
    {

        return $product = ProductPictures::find($id);
    }

    public function showImageByProduct($product_id)
    {

        $userId = 50; // Authorizer::getResourceOwnerId();

        $productPictures = ProductPictures::where('user_id', '=', $userId)->where('product_id', '=', $product_id)->get();

        return $productPictures;
    }

    public function getPicturesByProduct($product_id)
    {

        $productPictures = ProductPictures::where('product_id', '=', $product_id)->get();

        return $productPictures;
    }

    public function destroy($id)
    {
        $userId = 50; //Authorizer::getResourceOwnerId();
        $productPicture = ProductPictures::find($id);

        if ($userId == $productPicture->user_id) {
            $productPicture->delete();

            return Response::json(array("status" => "successful"), 200);
        } else return Response::json(array("status" => "fail"), 400);
    }


    public function mainPic()
    {
        $productId = Input::get('product_id');
        $id = Input::get('id');


        $pictures = DB::table('product_pictures')
            ->where('product_id', $productId)
            ->where('main_pic', 1)
            ->update('main_pic', 0);

        $picture = ProductPictures::find($id);
        $picture->main_pic = 1;
        $picture->save();

        return Response::json(array("status" => "successful"), 200);


    }


}