<?php

namespace Peytz\Debug;

use Psr\Log\LogLevel;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * @package PeytzRollbar
 */
class RollbarLogger extends \Psr\Log\AbstractLogger implements LoggerInterface
{
    const DEBUG     = 100;
    const INFO      = 200;
    const NOTICE    = 250;
    const WARNING   = 300;
    const ERROR     = 400;
    const CRITICAL  = 500;
    const ALERT     = 550;
    const EMERGENCY = 600;

    protected $notifier;
    protected $level;

    /**
     * @param string $accessToken
     */
    public function __construct(\RollbarNotifier $notifier, $level = LogLevel::ERROR)
    {
        $this->notifier = $notifier;
        $this->level = constant(__CLASS__ . '::' . strtoupper($level));

        register_shutdown_function(array($notifier, 'flush'));
    }

    /**
     * {@inheritDoc}
     */
    public function log($level, $message, array $context = array())
    {
        if (constant(__CLASS__ . '::' . strtoupper($level)) < $this->level) {
            return;
        }

        if (isset($context['exception'])) {
            $this->notifier->report_exception($context['exception']);

            return;
        }

        $this->notifier->report_message($message, $level, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function emerg($message, array $context = array())
    {
        $this->emergency($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function crit($message, array $context = array())
    {
        $this->critical($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function err($message, array $context = array())
    {
        $this->error($message, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function warn($message, array $context = array())
    {
        $this->warning($message, $context);
    }
}
