<?php
/**
 * Created by PhpStorm.
 * User: tonytlucas
 * Date: 4/20/15
 * Time: 11:54 AM
 */
class Order extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'order';
    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;
    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function user()
    {
        return $this->belongsTo('Users', 'user_id');
    }
     public function orderReceipt()
    {
        return $this->hasMany('OrderReceipt');
    }
      public function staff()
    {
        return $this->belongsTo('Users', 'staff_id');
    }

      public function timeline()
    {
        return $this->belongsToMany('Timeline')->withPivot('status_message','created_at','updated_at');
    }


    // ...
}