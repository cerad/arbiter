<?php
namespace Cerad\Component\Arbiter\Avail;

use Symfony\Component\Console\Command\Command;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Yaml\Yaml;

class AvailCommand extends Command
{
  protected $loader;

  public function __construct(AvailLoaderExcel $loader)
  {
    parent::__construct();

    $this->loader = $loader;
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

    return;

    print_r($loaderResults->dates);
    foreach($loaderResults->officials as $official) {
      print_r($official); die();
    }
  }
}