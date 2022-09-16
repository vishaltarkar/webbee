<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    #relantionships
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id', 'id')->with(['children']);
    }
}
