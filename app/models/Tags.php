<?php
/**
 * Created by PhpStorm.
 * User: tonytlucas
 * Date: 9/10/15
 * Time: 12:16 PM
 */
class Tags extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'tags';
    /**
     * Soft delete
     * @var boolean
     */
    protected $softDelete = true;
    /**
     * ORM (Modeling object relationships): goods in product category
     * @return object Illuminate\Database\Eloquent\Collection
     */
    public function product(){
        return $this->belongsToMany('Product');
    }
    public function category(){
        return $this->belongsToMany('ProductCategories');
    }
    public function forumCategory(){
        return $this->belongsToMany('ForumCategory');
    }
    public function forumTopic(){
        return $this->belongsToMany('ForumTopic');
    }
}
