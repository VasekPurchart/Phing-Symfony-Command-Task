<?php

declare(strict_types = 1);

namespace VasekPurchart\Phing\SymfonyCommand;

use Generator;
use PHPUnit\Framework\Assert;
use Project;
use VasekPurchart\Phing\PhingTester\PhingTester;

class SymfonyCommandTaskIntegrationTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @return mixed[][]|\Generator
	 */
	public function callCommandDataProvider(): Generator
	{
		yield 'call command' => (static function (): array {
			$target = 'testCallCommand';

			return [
				'target' => $target,
				'propertyNameWithExpectedExecutable' => $target . '.default.executable',
				'propertyNameWithExpectedApp' => $target . '.default.app',
			];
		})();

		yield 'call command with custom executable' => (static function (): array {
			$target = 'testCallCommandWithCustomExecutable';

			return [
				'target' => $target,
				'propertyNameWithExpectedExecutable' => $target . '.test.executable',
				'propertyNameWithExpectedApp' => $target . '.default.app',
			];
		})();

		yield 'call command with custom app' => (static function (): array {
			$target = 'testCallCommandWithCustomApp';

			return [
				'target' => $target,
				'propertyNameWithExpectedExecutable' => $target . '.default.executable',
				'propertyNameWithExpectedApp' => $target . '.test.app',
			];
		})();

		yield 'call command with custom executable and app' => (static function (): array {
			$target = 'testCallCommandWithCustomExecutableAndApp';

			return [
				'target' => $target,
				'propertyNameWithExpectedExecutable' => $target . '.test.executable',
				'propertyNameWithExpectedApp' => $target . '.test.app',
			];
		})();

		yield 'call command and override defaults' => (static function (): array {
			$target = 'testCallCommandAndOverrideDefaults';

			return [
				'target' => $target,
				'propertyNameWithExpectedExecutable' => $target . '.test.executable',
				'propertyNameWithExpectedApp' => $target . '.test.app',
			];
		})();
	}

	/**
	 * @dataProvider callCommandDataProvider
	 *
	 * @param string $target
	 * @param string $propertyNameWithExpectedExecutable
	 * @param string $propertyNameWithExpectedApp
	 */
	public function testCallCommand(
		string $target,
		string $propertyNameWithExpectedExecutable,
		string $propertyNameWithExpectedApp
	): void
	{
		$tester = new PhingTester(__DIR__ . '/symfony-command-task-integration-test.xml');
		$tester->executeTarget($target);

		$tester->assertLogMessageRegExp(sprintf(
			'~executing.+%s.+%s.+hello:world~i',
			$tester->getProject()->getProperty($propertyNameWithExpectedExecutable),
			$tester->getProject()->getProperty($propertyNameWithExpectedApp)
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

	public function testMissingApp(): void
	{
		$buildFilePath = __DIR__ . '/symfony-command-task-integration-test.xml';
		$tester = new PhingTester($buildFilePath);
		$target = __FUNCTION__;

		try {
			$tester->executeTarget($target);
			Assert::fail('Exception expected');
		} catch (\BuildException $e) {
			Assert::assertStringStartsWith($buildFilePath, $e->getLocation()->toString());
			Assert::assertRegExp('~app.+required~', $e->getMessage());
		}
	}

}
