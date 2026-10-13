<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewOrganizer(User $user, Event $event): bool
    {
        return $this->owns($user, $event) || $user->isAdmin();
    }

    public function update(User $user, Event $event): bool
    {
        return $this->viewOrganizer($user, $event);
    }

    public function managePromoCodes(User $user, Event $event): bool
    {
        return $this->viewOrganizer($user, $event);
    }

    public function scan(User $user, Event $event): bool
    {
        return $this->viewOrganizer($user, $event);
    }

    private function owns(User $user, Event $event): bool
    {
        return (int) $event->user_id === (int) $user->id;
    }
}
