<?php
/**
 * Created by PhpStorm.
 * User: tonytlucas
 * Date: 4/20/15
 * Time: 11:57 AM
 */
class OrderReceipt extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'order_receipt';
    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;
    /**
     * ORM (Modeling object relationships): Order
     * @return object User
     */
    public function order()
    {
        return $this->belongsTo('Order', 'order_id');
    }
    public function product()
    {
        return $this->belongsTo('Product', 'product_id');
    }
    // ...
}