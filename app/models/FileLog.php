<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 1/4/16
 * Time: 2:13 PM
 */


class FileLog extends BaseModel  {

    protected $table = 'log';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return "  SELECT log.* FROM log  ";
    }
    public static function queryWhere(  ){

        return "";
    }

    public static function queryGroup(){
        return "  ";
    }



}