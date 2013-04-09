<?php

namespace Peytz\Debug\Silex;

use Peytz\Debug\RollbarLogger;
use Silex\Application;

/**
 * @package Debug
 */
class DebugServiceProvider implements \Silex\ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Application $app)
    {
        $app['rollbar.access_token'] = null;
        $app['rollbar.level'] = 'error';

        $app['rollbar'] = $app->share(function ($app) {
            return new \RollbarNotifier(array('access_token' => $app['rollbar.access_token']));
        });

        $app['logger'] = $app->share(function ($app) {
            return new RollbarLogger($app['rollbar'], $app['rollbar.level']);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function boot(Application $app)
    {
    }
}
