<?php
namespace Cerad\Component\Arbiter\Schedule\Import;

use Symfony\Component\Console\Command\Command;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Yaml\Yaml;

use Zend\Diactoros\Stream;
use Zend\Diactoros\Response;

class ImportCommand extends Command
{
  protected $importer;

  public function __construct($importer = null)
  {
    parent::__construct();

    $this->importer = $importer;
  }
  protected function configure()
  {
    $this
      ->setName('schedule_import')
      ->setDescription('Import Arbiter Game Schedule')
      ->addArgument('filename');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $filename = $input->getArgument(('filename'));
    $basename = basename($filename);

    $parts = explode('-',$basename);

    $params = [
      'domain' => $parts[0],
      'season' => $parts[1],
      'sport'  => 'Soccer',

      'filename' => $filename,
      'basename' => $basename,
    ];
    echo sprintf("Import Schedule %s\n",$params['filename']);

    $results = $this->importer->import($params);

    print_r($results);
  }
}