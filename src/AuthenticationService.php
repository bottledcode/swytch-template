<?php

namespace Change\Me;

use BackedEnum;
use Bottledcode\SwytchFramework\Template\Interfaces\AuthenticationServiceInterface;

class AuthenticationService implements AuthenticationServiceInterface
{
    /**
     * Replace this with code to validate the current request is authentic, such as validating a session or token.
     *
     * @return bool Whether the user is authenticated or not.
     */
    public function isAuthenticated(): bool
    {
        return true;
    }

    /**
     * Validate that the current user can claim the given role(s).
     *
     * @param BackedEnum ...$role The role(s) to check for.
     * @return bool Whether the user is authorized or not.
     */
    public function isAuthorizedVia(BackedEnum ...$role): bool
    {
        return true;
    }
}
