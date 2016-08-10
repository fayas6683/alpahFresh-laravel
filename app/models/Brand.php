<?php

class Brand extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'brand';
    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;
    /**
     * ORM (Modeling object relationships): goods in product category
     * @return object Illuminate\Database\Eloquent\Collection
     */
      public function product()
    {
        return $this->belongsToMany('Product');
    }

}