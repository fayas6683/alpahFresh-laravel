<?php

class Timeline extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'timeline';
    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;
    /**
     * ORM (Modeling object relationships): goods in product category
     * @return object Illuminate\Database\Eloquent\Collection
     */

    public function order()
    {
          return $this->belongsToMany('Order')->withPivot('status_message','created_at','updated_at');
    }

function orders()
   {
       return $this->belongsToMany('Order')->withPivot('created_at');
   }

}