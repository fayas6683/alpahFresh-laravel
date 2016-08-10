<?php
/**
 * Created by PhpStorm.
 * User: diamatic ltd
 * Date: 12/15/15
 * Time: 5:10 PM
 */

class ImageController extends \BaseController {

    function scan_dir($dir) {
        $ignored = array('.', '..', '.svn', '.htaccess');

        $files = array();
        foreach (scandir($dir) as $file) {
            if (in_array($file, $ignored)) continue;
            $files[$file] = filemtime($dir . '/' . $file);
        }

        arsort($files);
        $files = array_keys($files);

        return ($files) ? $files : false;
    }


    public function imageLoad()
    {

        // Array of image objects to return.
        $response = array();

        // Image types.
        $image_types = array(
            "image/gif",
            "image/jpeg",
            "image/pjpeg",
            "image/jpeg",
            "image/pjpeg",
            "image/png",
            "image/x-png"
        );

        $accessToken      = Input::get('access_token');

        $existAccessToken = DB::select("SELECT COUNT(*) AS exist, os.owner_id FROM oauth_access_tokens oa
                                        LEFT JOIN oauth_sessions os ON os.id = oa.session_id
                                        WHERE expire_time > UNIX_TIMESTAMP(NOW()) AND oa.id LIKE '".$accessToken."'");

        if($existAccessToken){

            $userId           = $existAccessToken[0]->owner_id;

        }else{

            $userId           = 0;
        }


        $folder = public_path("uploads/uploads/".$userId);

        if(!is_dir($folder)) { mkdir($folder, 0777); }


        $fnames = $this->scan_dir($folder);

        if($fnames){
            foreach ($fnames as $name) {
                // Filename must not be a folder.
                if (!is_dir($name)) {
                    // Check if file is an image.
                    if (in_array(mime_content_type(public_path(). "/uploads/uploads/".$userId.'/'.$name), $image_types)) {
                        // Build the image.
                        $img        = new StdClass;
                        $img->url   = "/uploads/uploads/".$userId.'/'. $name;
                        $img->thumb = "/uploads/uploads/".$userId.'/'. $name;
                        $img->name  = $name;

                        // Add to the array of image.
                        array_push($response, $img);
                    }
                }
            }

            $response = json_encode($response);

            // Send response.
            echo stripslashes($response);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function storeImage()
    {
        $accessToken      = Input::get('access_token');

        $existAccessToken = DB::select("SELECT COUNT(*) AS exist, os.owner_id FROM oauth_access_tokens oa
                                        LEFT JOIN oauth_sessions os ON os.id = oa.session_id
                                        WHERE expire_time > UNIX_TIMESTAMP(NOW()) AND oa.id LIKE '".$accessToken."'");

        if($existAccessToken){

            $userId           = $existAccessToken[0]->owner_id;

        }else{

            $userId           = 0;
        }

        // Allowed extentions.
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

            $folder = public_path("uploads/uploads/".$userId);

            if(!is_dir($folder)) { mkdir($folder, 0777); }

            // Save file in the uploads folder.
            move_uploaded_file($_FILES["file"]["tmp_name"], public_path() . "/uploads/uploads/".$userId.'/' . $name);

            // Generate response.
            $response = new StdClass;
            $response->link = "/uploads/uploads/".$userId.'/'.$name;
            echo stripslashes(json_encode($response));
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function storeFile()
    {

        $userId = Authorizer::getResourceOwnerId();

        // Allowed extentions.
        $allowedExts = array("txt", "pdf", "doc");

        // Get filename.
        $temp = explode(".", $_FILES["file"]["name"]);

        // Get extension.
        $extension = end($temp);

        // Validate uploaded files.
        // Do not use $_FILES["file"]["type"] as it can be easily forged.
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);

        if ((($mime == "text/plain")
                || ($mime == "application/msword")
                || ($mime == "application/x-pdf")
                || ($mime == "application/pdf"))
            && in_array(strtolower($extension), $allowedExts)) {
            // Generate new random name.
            $name = sha1(microtime()) . "." . $extension;

            // Save file in the uploads folder.
            move_uploaded_file($_FILES["file"]["tmp_name"], public_path() . "/uploads/uploads/".$userId.'/' . $name);

            // Generate response.
            $response = new StdClass;
            $response->link = "/uploads/".$userId.'/'. $name;
            echo stripslashes(json_encode($response));
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

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroyFile()
    {

        $src = Input::get('src');

        if (file_exists(public_path() . $src)) {

            unlink(public_path() . $src);
        }

    }


}
