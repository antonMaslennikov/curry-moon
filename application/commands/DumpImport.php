<?php
namespace application\commands;

use Rah\Danpu\Dump;
use Rah\Danpu\Export;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpImport extends Command {

	protected function configure()
	{
		$this
			->setName('app:dump-import')
			->setDescription('Import dump sql in database')
			->setHelp('This command import dump sql in database"');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// outputs multiple lines to the console (adding "\n" at the end of each line)
		$output->writeln([
			'Create dump',
			'============',
			'',
		]);



		// outputs a message without adding a "\n" at the end of the line
		$output->write('You are about to create a dump.');
	}



}
