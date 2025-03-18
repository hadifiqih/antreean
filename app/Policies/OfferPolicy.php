<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Offer $offer)
    {
        return $user->sales && $user->sales->id === $offer->sales_id;
    }

    public function delete(User $user, Offer $offer)
    {
        return $user->sales && $user->sales->id === $offer->sales_id;
    }
}