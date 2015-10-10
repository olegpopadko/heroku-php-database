<?php

namespace OlegPopadko\HerokuDatabaseEnvironment\Composer;

use Composer\Script\Event;

class EnvironmentHandler
{
    public static function expand(Event $event)
    {
        $io = $event->getIO();

        if (!$databaseUrl = getenv('DATABASE_URL')) {
            $io->write('DATABASE_URL var is not set!');
            return null;
        }

        $database = [
            'host' => '127.0.0.1',
            'port' => 5432,
            'user' => null,
            'pass' => null,
        ];

        $database = array_merge($database, parse_url($databaseUrl));

        $vars = [
            'DATABASE_HOST=' . $database['host'],
            'DATABASE_PORT=' . $database['port'],
            'DATABASE_USER=' . $database['user'],
            'DATABASE_PASSWORD=' . $database['pass'],
            'DATABASE_NAME=' . str_replace('/', '', $database['path']),
        ];
        foreach ($vars as $var) {
            putenv($var);
            $io->write($var);
        }
    }
}
