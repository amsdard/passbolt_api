<?php

declare(strict_types=1);

namespace Amsdard\LdapAuthentication\Model;

class LdapUser
{
    protected string $email;

    protected string $firstName;

    protected string $lastName;

    public function __construct(array $attributes)
    {
        $this->email = $attributes['mail'][0];
        $this->firstName = $attributes['givenName'][0];
        $this->lastName = $attributes['sn'][0];
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }
}
