<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 11/2/15
 * Time: 11:54 AM
 */



class EmailMarketing extends Campaign  {

    protected $table = 'campaign';
    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();

        $this->campaign_type = 'email_marketing';

    }

    public static function querySelect(  ){


        return " SELECT  campaign.* FROM campaign ";
    }
    public static function queryWhere(  ){

        return " ";
    }

    public static function queryGroup(){
        return "      ";
    }

}
