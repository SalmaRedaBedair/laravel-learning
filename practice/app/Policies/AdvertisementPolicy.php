<?php

namespace App\Policies;

namespace App\Policies;

use App\Models\Adv;
use App\Models\User;

class AdvertisementPolicy
{
    public function view(User $user, Advertisement $advertisement)
    {
        // Define your authorization logic for viewing a photo
        return $user->id === $advertisement->user_id;
    }

    public function create(User $user)
    {
        // Define your authorization logic for creating a photo
        return $user->role === 'admin';
    }

    public function update(User $user, Advertisement $advertisement)
    {
        // Define your authorization logic for updating a photo
        return $user->id === $advertisement->user_id;
    }

    public function delete(User $user, Advertisement $advertisement)
    {
        // Define your authorization logic for deleting a photo
        return $user->id === $advertisement->user_id;
    }
}

