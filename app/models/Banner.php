<?php

class Banner extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'banner';
    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;
    /**
     * ORM (Modeling object relationships): goods in product category
     * @return object Illuminate\Database\Eloquent\Collection
     */

}