<?php
/**
 * Created by PhpStorm.
 * User: Diamatic.ltd
 * Date: 10/28/15
 * Time: 10:03 AM
 */


/**
 * AutoRespondMessage
 */

class AutoResponderPreference extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'auto_responder_preference';

    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * ORM (Modeling object relationships): goods in product category
     * @return object Illuminate\Database\Eloquent\Collection
     */
    public function user(){

        return $this->belongsTo('Users');
    }

    public function provider(){

        return $this->belongsTo('Provider');
    }
    // ...
}


