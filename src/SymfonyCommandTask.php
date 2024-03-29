<?php

declare(strict_types = 1);

namespace VasekPurchart\Phing\SymfonyCommand;

use CommandlineArgument;

class SymfonyCommandTask extends \ExecTask
{

	public const PROPERTY_DEFAULT_EXECUTABLE = 'symfony-command.default.executable';
	public const PROPERTY_DEFAULT_APP = 'symfony-command.default.app';

	/** @var bool check return code by default */
	protected $checkreturn = true;

	/** @var bool use passtru by default to enable continuous output */
	protected $passthru = true;

	/** @var string|null */
	private $executable;

	/** @var string|null path to Symfony application executable */
	private $app;

	/** @var string|null which command to run */
	private $cmd;

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 *
	 * @param string $executable
	 */
	public function setExecutable($executable): void
	{
		$this->executable = $executable;
		parent::setExecutable($executable);
	}

	public function setApp(string $app): void
	{
		$this->app = $app;
	}

	public function setCmd(string $cmd): void
	{
		$this->cmd = $cmd;
	}

	public function main(): void
	{
		if ($this->app === null) {
			$defaultApp = $this->getProject()->getProperty(self::PROPERTY_DEFAULT_APP);
			if ($defaultApp === null) {
				throw new \BuildException('Parameter "app" is required for SymfonyCommand');
			}
			$this->app = $defaultApp;
		}
		if ($this->cmd !== null) {
			array_unshift($this->commandline->arguments, $this->createCmdArgument());
		}
		$appAsExecutable = $this->executable === null;
		if ($this->executable === null) {
			$defaultExecutable = $this->getProject()->getProperty(self::PROPERTY_DEFAULT_EXECUTABLE);
			if ($defaultExecutable !== null) {
				$this->commandline->executable = $defaultExecutable;
				$appAsExecutable = false;
			}
		}
		if ($appAsExecutable) {
			$this->commandline->executable = $this->app;
		} else {
			array_unshift($this->commandline->arguments, $this->createAppArgument());
		}

		parent::main();
	}

	private function createAppArgument(): CommandlineArgument
	{
		$argument = new CommandlineArgument($this->commandline);
		$argument->setPath($this->app);

		return $argument;
	}

	private function createCmdArgument(): CommandlineArgument
	{
		$argument = new CommandlineArgument($this->commandline);
		$argument->setValue($this->cmd);

		return $argument;
	}

}
