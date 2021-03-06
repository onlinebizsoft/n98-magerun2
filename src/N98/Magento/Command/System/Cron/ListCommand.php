<?php

namespace N98\Magento\Command\System\Cron;

use N98\Util\Console\Helper\Table\Renderer\RendererFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends AbstractCronCommand
{
    protected function configure()
    {
        $this
            ->setName('sys:cron:list')
            ->setDescription('Lists all cronjobs')
            ->addOption(
                'format',
                null,
                InputOption::VALUE_OPTIONAL,
                'Output Format. One of [' . implode(',', RendererFactory::getFormats()) . ']'
            )
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('format') === null) {
            $this->writeSection($output, 'Cronjob List');
        }

        $table = $this->getJobs();
        $this->getHelper('table')
            ->setHeaders(array_keys(current($table)))
            ->renderByFormat($output, $table, $input->getOption('format'));
    }
}
