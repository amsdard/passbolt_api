<?php
declare(strict_types=1);

namespace Amsdard\LdapAuthentication\Middleware;

use Amsdard\LdapAuthentication\Service\LdapAuthenticationService;
use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LdapMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request The request.
     * @param RequestHandlerInterface $handler The handler.
     *
     * @return ResponseInterface The response.
     */
    public function process(
        ServerRequestInterface  $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        /** @var \Cake\Http\ServerRequest $request */
        $userId = $request->getSession()->read('Auth.user.id');

        if (!is_string($userId) && $this->isRouteWhiteListed($request)) {
            $ldapAuthenticationService = new LdapAuthenticationService($request);

            $ldapAuthenticationService->authenticate();
        }

        return $handler->handle($request);
    }

    /**
     * @param \Cake\Http\ServerRequest $request request
     *
     * @return bool
     */
    protected function isRouteWhiteListed(ServerRequestInterface $request): bool
    {
        // Do not redirect on mfa setup or check page
        // same goes for authentication pages
        $whitelistedPaths = [
            '/users/recover',
            '/login',
            '/auth/login',
            '/auth/jwt/login',
            '/mfa/verify',
            '/auth/logout',
            '/logout',
        ];
        foreach ($whitelistedPaths as $path) {
            $uriPath = str_replace('.json', '', $request->getUri()->getPath());

            if (substr($uriPath, 0, strlen($path)) === $path) {
                return true;
            }
        }

        return false;
    }
}
