<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 30.11.2017
 * Time: 19:35
 */

namespace application\commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class DumpExport extends Command {

	protected function configure()
	{
		$this
			->setName('app:dump-export')
			->setDescription('Create dump database MySQL')
			->setHelp('This command create dump databa /dump.sql"');
	}

	protected function basePath(){

		return (implode(DIRECTORY_SEPARATOR, [
			dirname(__FILE__),
			'..',
			'..',
		]));
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// outputs multiple lines to the console (adding "\n" at the end of each line)
		$output->writeln([
			'Create dump',
			'============',
			'',
		]);

		require_once implode(DIRECTORY_SEPARATOR, [
			$this->basePath(),
			'application',
			'configs',
			'main.php'
		]);


		$this->export($output);


	}

	protected function export(OutputInterface $output) {

		try {

			$cmd = sprintf(
			"mysqldump -h %s -u %s --password=%s %s  > %s",
				DBHOST,
				DBUSER,
				DBPASS,
				DBNAME,
				$this->basePath().DIRECTORY_SEPARATOR.'runtime'.DIRECTORY_SEPARATOR.'example.sql'
				);
			exec($cmd);

			$zip = new \ZipArchive();
			

			// outputs a message without adding a "\n" at the end of the line
			$output->writeln('Create a dump success!');

		} catch (\Exception $e) {
			$output->writeln('Export failed with message: ' . $e->getMessage());
		}
	}
}