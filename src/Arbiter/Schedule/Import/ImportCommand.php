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

    echo sprintf("Import Schedule %s\n",$filename);

    return;

    $loaderResults = $this->loader->load($filename);

    $data = [
      'dates'     => $loaderResults->dates,
      'officials' => $loaderResults->officials,
    ];
    file_put_contents($filename . '.yml',Yaml::dump($data,10));

    echo sprintf("Loaded Dates %s, Officials %d\n",count($loaderResults->dates),count($loaderResults->officials));

    $reporter = $this->reporter;

    $reporter->report($loaderResults->dates,$loaderResults->officials);

    $out = $filename . '.' . $reporter->getFileExtension();
    $reporter->save($out);

    $response = new Response($out,200,[]);
    return;

    $stream = new Stream($out);
    return;

    $fp = fopen('php://temp','r+');
    fputs($fp,$reporter->getContents());
    rewind($fp);


    $stream = new Stream($fp);
    file_put_contents($filename . '.xlsx', $stream->getContents());

    return;
    file_put_contents($filename . '.xlsx', $reporter->getContents());
  }
}