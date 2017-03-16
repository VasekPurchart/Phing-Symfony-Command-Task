<?php

declare(strict_types = 1);

namespace VasekPurchart\Phing\SymfonyCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorldCommand extends \Symfony\Component\Console\Command\Command
{

	const NAME = 'hello:world';

	protected function configure()
	{
		$this->setName(self::NAME);
		$description = 'Hello world test command';
		$this->setDescription($description);
		$this->setHelp($description);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// empty
	}

}
