<?php

namespace App\Events\Contracts;

use App\Models\Contracts\Influencable;

interface InfluencableEvent
{
    public function getInfluencable() : Influencable;
}
