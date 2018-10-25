<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyPublisher extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'survey_publishers';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'survey_id',
        'publisher_id',
        'link1',
        'link2',
        'link3',
        'status',
    ];
}
