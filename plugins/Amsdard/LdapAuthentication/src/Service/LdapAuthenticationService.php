<?php

namespace Amsdard\LdapAuthentication\Service;

use Amsdard\LdapAuthentication\Model\LdapUser;
use Cake\Datasource\ModelAwareTrait;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Ldap;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class LdapAuthenticationService
{
    use ModelAwareTrait;

    public const USERNAME_IDENTIFIER = 'username';

    protected ServerRequest $request;

    protected ?Ldap $connection = null;

    protected string $baseDn;

    protected bool $isBinded = true;

    protected string $toolCn;

    /**
     * @param ServerRequest $serverRequest Server request
     */
    public function __construct(ServerRequest $request)
    {
        $this->request = $request;
        $this->connect();

        $this->loadModel('Users');
    }

    /**
     * Create connection and
     *
     * @return void
     */
    public function connect(): void
    {
        $this->baseDn = Configure::readOrFail('ldap.dn');
        $this->toolCn = Configure::readOrFail('ldap.tool_cn');

        $host = Configure::readOrFail('ldap.host');
        $username = Configure::readOrFail('ldap.username');
        $password = Configure::readOrFail('ldap.password');

        $connection = Ldap::create('ext_ldap', [
            'host' => $host,
            'encryption' => 'ssl',
        ]);

        try {
            $connection->bind('cn=' . $username . ',' . $this->baseDn, $password);
        } catch (\Exception $e) {
            dd($e);
            $this->isBinded = false;
        }

        $this->connection = $connection;
    }

    /**
     * Authenticates LDAP user and registers new user in application
     *
     * @throws \App\Error\Exception\ValidationException
     * @return void
     */
    public function authenticate(): void
    {
        if (!$this->isBinded) {
            return;
        }

        $email = $this->request->getData(self::USERNAME_IDENTIFIER);

        if (!$email) {
            return;
        }

        $user = $this->Users->findByUsername($email)->first();

        if ($user) {
            return;
        }

        $ldapUser = $this->findToolUserByEmail($email);

        if (is_null($ldapUser)) {
            return;
        }

        $this->Users->register([
            'username' => $ldapUser->getEmail(),
            'profile' => [
                'first_name' => $ldapUser->getFirstName(),
                'last_name' => $ldapUser->getLastName(),
            ],
        ]);
    }

    /**
     *
     *
     * @param string $email
     *
     * @return LdapUser|null
     */
    protected function findToolUserByEmail(string $email): ?LdapUser
    {
        $query = $this->connection->query("ou=users,{$this->baseDn}", "(mail=$email)");
        $results = $query->execute();

        /** @var Entry|null $ldapUser */
        $ldapUser = $results[0];

        if (!isset($ldapUser) || !$this->authorizeToolAccess($ldapUser)) {
            return null;
        }

        return new LdapUser($ldapUser->getAttributes());
    }

    /**
     * Authorizes if given user has access to tool
     *
     * @param Entry $entry
     *
     * @return bool
     */
    protected function authorizeToolAccess(Entry $entry): bool
    {
        $query = $this->connection->query(sprintf(
            'cn=%s,ou=tools,ou=groups,%s',
            $this->toolCn,
            $this->baseDn
        ), "cn=$this->toolCn");
        $results = $query->execute();

        /** @var Entry|null $toolEntry */
        $toolEntry = $results[0];

        if (!isset($toolEntry)) {
            return false;
        }

        return in_array($entry->getDn(), $toolEntry->getAttribute('uniqueMember'));
    }
}
