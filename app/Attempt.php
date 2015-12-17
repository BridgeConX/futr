<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Attempt extends Eloquent
{

    /**
     * @var array
     */
    protected $fillable = [
      'try_at',
    ];

    /**
     * @var array
     */
    protected $dates = [
      'tried_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
      'response' => 'object',
    ];

    /**
     * @var array
     */
    protected $appends = [
      'tried',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * @return bool
     */
    protected function getTriedAttribute()
    {
        return ! is_null($this->tried_at);
    }

}