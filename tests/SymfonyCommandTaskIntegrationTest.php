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
			$target = 'call-command';

			return [
				'target' => $target,
				'propertyNameWithExpectedExecutable' => $target . '.default.executable',
				'propertyNameWithExpectedApp' => $target . '.default.app',
			];
		})();

		yield 'call command with custom executable' => (static function (): array {
			$target = 'call-command-with-custom-executable';

			return [
				'target' => $target,
				'propertyNameWithExpectedExecutable' => $target . '.test.executable',
				'propertyNameWithExpectedApp' => $target . '.default.app',
			];
		})();

		yield 'call command with custom app' => (static function (): array {
			$target = 'call-command-with-custom-app';

			return [
				'target' => $target,
				'propertyNameWithExpectedExecutable' => $target . '.default.executable',
				'propertyNameWithExpectedApp' => $target . '.test.app',
			];
		})();

		yield 'call command with custom executable and app' => (static function (): array {
			$target = 'call-command-with-custom-executable-and-app';

			return [
				'target' => $target,
				'propertyNameWithExpectedExecutable' => $target . '.test.executable',
				'propertyNameWithExpectedApp' => $target . '.test.app',
			];
		})();

		yield 'call command and override defaults' => (static function (): array {
			$target = 'call-command-and-override-defaults';

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
		$target = 'call-command-with-app-as-executable';
		$tester->executeTarget($target);

		$tester->assertLogMessageRegExp(sprintf(
			'~executing.+%s.+hello:world~i',
			$tester->getProject()->getProperty($target . '.test.app')
		), $target, Project::MSG_VERBOSE);
	}

	public function testMissingApp(): void
	{
		$buildFilePath = __DIR__ . '/symfony-command-task-integration-test.xml';
		$tester = new PhingTester($buildFilePath);
		$target = 'missing-app';

		try {
			$tester->executeTarget($target);
			Assert::fail('Exception expected');
		} catch (\BuildException $e) {
			Assert::assertStringStartsWith($buildFilePath, $e->getLocation()->toString());
			Assert::assertRegExp('~app.+required~', $e->getMessage());
		}
	}

}
