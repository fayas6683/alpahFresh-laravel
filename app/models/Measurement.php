<?php

class Measurement extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'measurement_unit';
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