<?php

declare(strict_types = 1);

namespace VasekPurchart\Phing\SymfonyCommand;

use Project;
use VasekPurchart\Phing\PhingTester\PhingTester;

class SymfonyCommandTaskIntegrationTest extends \PHPUnit\Framework\TestCase
{

	public function testCallCommand(): void
	{
		$tester = new PhingTester(__DIR__ . '/symfony-command-task-integration-test.xml');
		$target = __FUNCTION__;
		$tester->executeTarget($target);

		$tester->assertLogMessageRegExp(sprintf(
			'~executing.+%s.+%s.+hello:world~i',
			$tester->getProject()->getProperty(__FUNCTION__ . '.default.executable'),
			$tester->getProject()->getProperty(__FUNCTION__ . '.default.app')
		), $target, Project::MSG_VERBOSE);
	}

	public function testCallCommandWithCustomExecutable(): void
	{
		$tester = new PhingTester(__DIR__ . '/symfony-command-task-integration-test.xml');
		$target = __FUNCTION__;
		$tester->executeTarget($target);

		$tester->assertLogMessageRegExp(sprintf(
			'~executing.+%s.+%s.+hello:world~i',
			$tester->getProject()->getProperty(__FUNCTION__ . '.test.executable'),
			$tester->getProject()->getProperty(__FUNCTION__ . '.default.app')
		), $target, Project::MSG_VERBOSE);
	}

	public function testCallCommandWithCustomApp(): void
	{
		$tester = new PhingTester(__DIR__ . '/symfony-command-task-integration-test.xml');
		$target = __FUNCTION__;
		$tester->executeTarget($target);

		$tester->assertLogMessageRegExp(sprintf(
			'~executing.+%s.+%s.+hello:world~i',
			$tester->getProject()->getProperty(__FUNCTION__ . '.default.executable'),
			$tester->getProject()->getProperty(__FUNCTION__ . '.test.app')
		), $target, Project::MSG_VERBOSE);
	}

	public function testCallCommandWithCustomExecutableAndApp(): void
	{
		$tester = new PhingTester(__DIR__ . '/symfony-command-task-integration-test.xml');
		$target = __FUNCTION__;
		$tester->executeTarget($target);

		$tester->assertLogMessageRegExp(sprintf(
			'~executing.+%s.+%s.+hello:world~i',
			$tester->getProject()->getProperty(__FUNCTION__ . '.test.executable'),
			$tester->getProject()->getProperty(__FUNCTION__ . '.test.app')
		), $target, Project::MSG_VERBOSE);
	}

	public function testCallCommandWithAppAsExecutable(): void
	{
		$tester = new PhingTester(__DIR__ . '/symfony-command-task-integration-test.xml');
		$target = __FUNCTION__;
		$tester->executeTarget($target);

		$tester->assertLogMessageRegExp(sprintf(
			'~executing.+%s.+hello:world~i',
			$tester->getProject()->getProperty(__FUNCTION__ . '.test.app')
		), $target, Project::MSG_VERBOSE);
	}

	public function testCallCommandAndOverrideDefaults(): void
	{
		$tester = new PhingTester(__DIR__ . '/symfony-command-task-integration-test.xml');
		$target = __FUNCTION__;
		$tester->executeTarget($target);

		$tester->assertLogMessageRegExp(sprintf(
			'~executing.+%s.+%s.+hello:world~i',
			$tester->getProject()->getProperty(__FUNCTION__ . '.test.executable'),
			$tester->getProject()->getProperty(__FUNCTION__ . '.test.app')
		), $target, Project::MSG_VERBOSE);
	}

	public function testMissingApp(): void
	{
		$tester = new PhingTester(__DIR__ . '/symfony-command-task-integration-test.xml');
		$target = __FUNCTION__;

		$this->expectException(\BuildException::class);
		$this->expectExceptionMessageMatches('~app.+required~');

		$tester->executeTarget($target);
	}

}
