<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 12/9/15
 * Time: 9:35 AM
 */


class Visitor extends BaseModel  {

    protected $table = 'visitor';
    protected $primaryKey = 'id';


    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){


        return " SELECT  visitor.* FROM visitor ";
    }
    public static function queryWhere(  ){

        return " ";
    }

    public static function queryGroup(){
        return "      ";
    }

    public function custom_templates(){

        return $this->belongsTo('CustomTemplates');

    }

    public function parent(){

        return $this->belongsTo('CustomTemplates','parent_id');

    }
}
