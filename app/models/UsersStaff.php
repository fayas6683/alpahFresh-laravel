<?php
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 8/3/15
 * Time: 1:37 PM
 */


class UsersStaff extends BaseModel
{
    /**
     * Database table (without prefix)
     * @var string
     */
    protected $table = 'users_staff';

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

    public function staff()
    {
        return $this->belongsTo('Users', 'staff_id');
    }




}
