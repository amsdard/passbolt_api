<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.2.0
 */
namespace Amsdard\LdapAuthentication;

use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Passbolt\Locale\Event\LocaleEmailQueueListener;
use Passbolt\Locale\Event\LocaleRenderListener;
use Passbolt\Locale\Event\SaveUserLocaleListener;
use Passbolt\Locale\Event\ValidateLocaleOnBeforeSaveListener;
use Amsdard\LdapAuthentication\Middleware\LdapMiddleware;

class Plugin extends BasePlugin
{
    /**
     * @inheritDoc
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);
    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue->insertAfter(
            AuthenticationMiddleware::class,
            LdapMiddleware::class
        );
    }
}
