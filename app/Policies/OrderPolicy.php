<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user)
    {
        // example: users with role 'admin' or 'staff'
        return in_array($user->role ?? 'user', ['admin', 'staff']);
    }

    public function view(User $user, Order $order)
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Order $order)
    {
        return $this->viewAny($user);
    }
}
