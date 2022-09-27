<?php

namespace PawelMysior\Publishable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait Publishable
{
    /**
     * Scope a query to only include published models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query)
    {
        return $query->where('published_at', '<=', Carbon::now())->whereNotNull('published_at');
    }

    /**
     * Scope a query to only include unpublished models.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpublished(Builder $query)
    {
        return $query->where('published_at', '>', Carbon::now())->orWhereNull('published_at');
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        if (is_null($this->published_at)) {
            return false;
        }

        return $this->published_at->lte(Carbon::now());
    }

    /**
     * @return bool
     */
    public function isUnpublished()
    {
        return !$this->isPublished();
    }

    /**
     * @return bool
     */
    public function publish()
    {
        $this->published_at = Carbon::now();

        return $this->save();
    }

    /**
     * Publishes the model without firing events.
     *
     * @return bool
     */
    public function publishQuietly()
    {
        $this->published_at = Carbon::now();

        return $this->saveQuietly();
    }

    /**
     * @return bool
     */
    public function unpublish()
    {
        $this->published_at = null;

        return $this->save();
    }

    /**
     * Unpublishes the model without firing events.
     *
     * @return bool
     */
    public function unpublishQuietly()
    {
        $this->published_at = null;

        return $this->saveQuietly();
    }

    /**
     * @param  mixed $value
     * @return \Carbon\Carbon
     */
    public function getPublishedAtAttribute($value)
    {
        if (is_null($value)) {
            return $value;
        }
        
        return $this->asDateTime($value);
    }
}
