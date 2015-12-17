<?php

namespace App;

use App\Support\HasDateOrTimeColumns;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Eloquent
{

    use HasDateOrTimeColumns, SoftDeletes;

    /**
     * @var string
     */
    protected $timeUserFormat = 'H:i';

    /**
     * @var array
     */
    protected $fillable = [
      'payload',
      'callback_url',
      'start_at',
      'time',
      'retries',
      'retry_interval',
    ];

    /**
     * @var array
     */
    protected $dates = [
      'start_at',
    ];

    /**
     * @var array
     */
    protected $timeColumns = [
      'time',
    ];

    /**
     * @var array
     */
    protected $casts = [
      'payload'        => 'array',
      'retries'        => 'integer',
      'retry_interval' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }
}