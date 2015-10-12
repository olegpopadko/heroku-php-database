<?php

namespace OlegPopadko\HerokuDatabaseEnvironment\Tests;

use OlegPopadko\HerokuDatabaseEnvironment\Composer\EnvironmentHandler;

class EnvironmentHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $host = 'database.host';
        $port = 5432;
        $user = 'user';
        $password = 'password';
        $name = 'database_name';

        $databaseUrl = sprintf('postgres://%s:%s@%s:%s/%s', $user, $password, $host, $port, $name);

        putenv('DATABASE_URL=' . $databaseUrl);

        $io = $this->getMock('Composer\IO\IOInterface');

        $io->expects($this->exactly(5))
            ->method('write')
            ->with($this->logicalOr(
                $this->equalTo('DATABASE_HOST=' . $host),
                $this->equalTo('DATABASE_PORT=' . $port),
                $this->equalTo('DATABASE_USER=' . $user),
                $this->equalTo('DATABASE_PASSWORD=' . $password),
                $this->equalTo('DATABASE_NAME=' . $name)
            ));

        $event = $this->getMockBuilder('Composer\Script\Event')->disableOriginalConstructor()->getMock();

        $event->expects($this->once())
            ->method('getIO')
            ->willReturn($io);

        EnvironmentHandler::expand($event);

        $this->assertEquals($host, getenv('DATABASE_HOST'));
        $this->assertEquals($port, getenv('DATABASE_PORT'));
        $this->assertEquals($user, getenv('DATABASE_USER'));
        $this->assertEquals($password, getenv('DATABASE_PASSWORD'));
        $this->assertEquals($name, getenv('DATABASE_NAME'));
    }

    public function testEmptyDatabaseUrl()
    {
        putenv('DATABASE_URL=');

        $io = $this->getMock('Composer\IO\IOInterface');

        $io->expects($this->once())
            ->method('write')
            ->with('DATABASE_URL var is not set!');

        $event = $this->getMockBuilder('Composer\Script\Event')->disableOriginalConstructor()->getMock();

        $event->expects($this->once())
            ->method('getIO')
            ->willReturn($io);

        EnvironmentHandler::expand($event);
    }
}
