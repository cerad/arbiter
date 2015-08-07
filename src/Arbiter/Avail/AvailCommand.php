<?php
namespace Cerad\Component\Arbiter\Avail;

use Symfony\Component\Console\Command\Command;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Yaml\Yaml;

use Zend\Diactoros\Stream;
use Zend\Diactoros\Response;

class AvailCommand extends Command
{
  protected $loader;
  protected $reporter;

  public function __construct(AvailLoaderExcel $loader, AvailReporterExcel $reporter)
  {
    parent::__construct();

    $this->loader   = $loader;
    $this->reporter = $reporter;

  }
  protected function configure()
  {
    $this
      ->setName('avail')
      ->setDescription('Generate Availability Report')
      ->addArgument('filename');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $filename = $input->getArgument(('filename'));

    echo sprintf("Avail Report %s\n",$filename);

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