<?php

namespace App;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Builder;
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
      'response' => 'array',
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
     * @param Builder $query
     *
     * @return Builder|static
     */
    public function scopePending(Builder $query)
    {
        return $query
          ->where('try_at', '<=', Carbon::now())
          ->whereNull('tried_at')
          ->has('job')->with('job');
    }

    /**
     * @return bool
     */
    public function getTriedAttribute()
    {
        return ! is_null($this->tried_at);
    }

    /**
     * @param          $statusCode
     * @param Response $response
     *
     * @return Attempt
     */
    public function markAttempted($statusCode, Response $response)
    {
        $this->tried_at = Carbon::now();
        $this->status_code = $statusCode;
        $this->response = [
          'headers' => $response->getHeaders(),
          'body'    => utf8_encode((string) $response->getBody()),
        ];

        $this->save();

        return $this;
    }

}