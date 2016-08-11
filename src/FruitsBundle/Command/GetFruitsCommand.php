<?php

namespace FruitsBundle\Command;

use FruitsBundle\Service\GetFruitDetailService;
use FruitsBundle\Service\GetFruitsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetFruitsCommand extends Command
{
    private $fruitsService;

    public function __construct(GetFruitsService $fruitsService)
    {
        $this->fruitsService = $fruitsService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:get-fruits')
            ->setDescription('Get information about fruits');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->fruitsService->processPage(
            'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html'
        );

        $output->write(json_encode($result));
    }
}