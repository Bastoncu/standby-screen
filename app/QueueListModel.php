<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueListModel extends Model
{
    protected $table = 'queue_lists';
    protected $fillable = [
        'class_id', 'status', 'name', 'surname'
    ];

    protected $appends = [ 'fullname' ];

    public function getFullnameAttribute()
    {
        return $this->name .' '. $this->surname;
    }
}
