<?php

namespace App;

use Monolog\Logger as Logger;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\IntrospectionProcessor;

use App\Base;

/*
 * @class App\AppLogger
 *
 * This provides a simple way to get a Monolog logger.
 */
class AppLogger extends Base {
    /*
     * The single instance.
     */
    private static $instance;

    /*
     * The Monolog handler.
     */
    private $log_handler;

    /*
     * The default log options.
     */
    private static $default_log_options = [
        'name'        => 'foo-bar',
        'log_handler' => null,
        'level'       => Logger::DEBUG,
        'formatter'   => null,
    ];

    /*
     * Logger constructor.
     *
     * Provides a consistent logging instance.
     *
     * @param $log_opts An hash of log options.
     *
     * @return A configured object.
     */
    function __construct($log_opts = []) {
        parent::__construct($log_opts);
        return $this;
    }

    /*
     * Gets the singleton instance.
     */
    public static function getInstance($log_opts = []) {
        if (static::$instance === null) {
            $log_options = array_merge(
                static::$default_log_options,
                $log_opts
            );
            
            $log_options['level'] = Logger::DEBUG;

            $logger = new Logger($log_options['name']);

            if (! isset($log_options['log_handler'])) {
                $log_handler = new ErrorLogHandler(
                    ErrorLogHandler::OPERATING_SYSTEM,
                    $log_options['level']
                 );

            } else {
                $log_handler = $log_options['log_handler'];
            }
                
            $line_format = "%channel%.%level_name%:\n\t%message% %context%\n\n".
                "\t%extra%";

            $formatter = (isset($log_options['formatter']))
                ? $log_options['formatter']
                : new LineFormatter($line_format, null, true, null);

            $log_handler->setFormatter($formatter);
            
            $processor = new IntrospectionProcessor();
            $log_handler->pushProcessor($processor);

            $logger->pushHandler($log_handler);

            /*
             * Note: Don't enable this on pages which may redirect!
             * 
             * Disabled by default.
             */

            //$log_handler = new BrowserConsoleHandler();
            //$log_handler->setFormatter($formatter);

            //$log_handler->pushProcessor($processor);

            //$logger->pushHandler($log_handler);

            static::$instance = $logger;
        }

        return static::$instance;
    }

    /*
     * Singleton - no cloning!
     */
    private function __clone() {
    }

    /*
     * Singleton - no wakeup!
     */
    private function __wakeup() {
    }
}
