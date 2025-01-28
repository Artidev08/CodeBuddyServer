<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasFormattedTimestamps
{
    public function formattedCreatedAt(): Attribute
    {
        $format = 'jS M, Y';
        $timezone = auth()->user()->timezone ?? 'UTC';
        $utz_time = $this->created_at ?? now();

        if (\Auth::check() && auth()->user()->timezone != null) {
            $utz_time = $utz_time->setTimezone($timezone);
        }
        return Attribute::make(
            get: fn ($value) => $utz_time->format($format),
        );
    }
    public function formattedUpdatedAt(): Attribute
    {
        $format = 'jS M, Y';
        return Attribute::make(
            get: fn ($value) => $this->updated_at->format($format),
        );
    }
}
