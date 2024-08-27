<?php

namespace Swordbros\ThemeExtender\Classes;

use RainLab\User\Models\User;

class ExtendUserModel
{
    public function subscribe()
    {
        User::extend(function (User $user) {
            $this->addProperties($user);

            $user->addDynamicMethod('getFullNameAttribute', function () use ($user) {
                return $user->name . ' ' . $user->surname;
            });
        });
    }

    private function addProperties(User $user): void
    {
        $user->addFillable([
            'phone',
        ]);
    }
}
