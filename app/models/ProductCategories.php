<?php
/**
 * Created by PhpStorm.
 * User: tonytlucas
 * Date: 4/10/15
 * Time: 11:43 AM
 */
/**
 * Product categories
 */
class ProductCategories extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'product_categories';
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
    public function tags(){
        return $this->belongsToMany('Tags');
    }
}