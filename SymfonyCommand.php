<?php

/**
 * @author Vašek Purchart
 */
class SymfonyCommand extends \ExecTask
{

	const PROPERTY_DEFAULT_EXECUTABLE = 'symfony-command.default.executable';
	const PROPERTY_DEFAULT_APP = 'symfony-command.default.app';

	/** @var boolean check return code by default */
	protected $checkreturn = true;

	/** @var boolean use passtru by default to enable continuous output */
	protected $passthru = true;

	/** @var string path tu symfony application executable */
	private $app;

	/** @var string|null which command to run */
	private $cmd;

	/**
	 * @param string $app
	 */
	public function setApp($app)
	{
		$this->app = $app;
	}

	/**
	 * @param string $cmd
	 */
	public function setCmd($cmd)
	{
		$this->cmd = $cmd;
	}

	public function main()
	{
		if ($this->app === null) {
			$defaultApp = $this->getProject()->getProperty(self::PROPERTY_DEFAULT_APP);
			if ($defaultApp === null) {
				throw new \BuildException('Parameter app  is required for SymfonyCommand');
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

	/**
	 * @return \CommandlineArgument
	 */
	private function createAppArgument()
	{
		$argument = new CommandlineArgument($this->commandline);
		$argument->setPath($this->app);

		return $argument;
	}

	/**
	 * @return \CommandlineArgument
	 */
	private function createCmdArgument()
	{
		$argument = new CommandlineArgument($this->commandline);
		$argument->setValue($this->cmd);

		return $argument;
	}

}
