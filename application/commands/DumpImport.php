<?php
namespace application\commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpImport extends Command {

	protected function basePath(){

		return (implode(DIRECTORY_SEPARATOR, [
			dirname(__FILE__),
			'..',
			'..',
		]));
	}

	protected function configure()
	{
		$this
			->setName('import-sql')
			->setDescription('Import dump sql in database')
			->setHelp('This command import dump sql in database"');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// outputs multiple lines to the console (adding "\n" at the end of each line)
		$output->writeln([
			'Import dump',
			'============',
			'',
		]);

		require_once implode(DIRECTORY_SEPARATOR, [
			$this->basePath(),
			'application',
			'configs',
			'main.php'
		]);

		$this->import($output);
	}


	protected function import(OutputInterface $output) {

		try {

			$cmd = sprintf(
				'mysql --host=%s --user=%s --password=%s %s < %s;',
				DBHOST,
				DBUSER,
				DBPASS,
				DBNAME,
				$this->basePath().DIRECTORY_SEPARATOR.'dump.sql'
			);

			//exec($cmd);
			$output->writeln("Run command: $cmd");
			exec($cmd ,$out, $status);

			if ($status)
				$output->writeln("Import success!");
			else
				$output->writeln(implode(PHP_EOL, $out));


		} catch (\Exception $e) {

			$output->writeln('Import failed with message: ' . $e->getMessage());
		}
	}
}
