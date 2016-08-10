<?php
/**
 * Created by PhpStorm.
 * User: tonytlucas
 * Date: 4/10/15
 * Time: 11:36 AM
 */
use Nicolaslopezj\Searchable\SearchableTrait;
class Product extends BaseModel  {
    use SearchableTrait;
    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $searchable = [
        'columns' => [
            'title' => 3,
        ],

    ];

   
    /**
     * ORM (Modeling object relationships): Seller
     * @return object User
     */
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
    /**
     * ORM (Modeling object relationships): Comments of goods
     * @return object Illuminate\Database\Eloquent\Collection
     */
 
    /**
     * ORM (Modeling object relationships): Picture of goods
     * @return object Illuminate\Database\Eloquent\Collection
     */
    public function pictures()
    {
        return $this->hasMany('ProductPictures', 'product_id');
    }
    // public function vendor(){
    //     return $this->belongsTo('Users', 'vendor_id');
    // }
    public function tags(){
        return $this->belongsToMany('Tags');
    }

    public function category()
    {
        return $this->belongsToMany('ProductCategories');
    }

     public function brand()
    {
        return $this->belongsToMany('Brand');
    }

     public function vendor()
    {
        return $this->belongsToMany('Users');
    }

     public function measurementUnit()
     {
         return $this->belongsTo('Measurement', 'measurement_unit');
     }
 
}