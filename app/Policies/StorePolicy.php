<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;

class StorePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function manage(User $user, Store $store)
    {
        return $user->id === $store->owner_id;
    }

}
