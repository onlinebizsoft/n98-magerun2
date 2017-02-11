<?php

namespace N98\Magento\Command\Developer\Console;

use Magento\Framework\Code\Generator\ClassGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Code\Generator\FileGenerator;

class MakeHelperCommand extends AbstractGeneratorCommand
{
    protected function configure()
    {
        $this
            ->setName('make:helper')
            ->addArgument('classpath', InputArgument::REQUIRED)
            ->setDescription('Creates a helper class');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void
     */
    protected function catchedExecute(InputInterface $input, OutputInterface $output)
    {
        $classFileName = $this->getNormalizedPathByArgument($input->getArgument('classpath'));
        $classNameToGenerate = $this->getCurrentModuleNamespace()
            . '\\Helper\\'
            . $this->getNormalizedClassnameByArgument($input->getArgument('classpath'));
        $filePathToGenerate = 'Helper/' . $classFileName . '.php';

        $classGenerator = $this->create(ClassGenerator::class);

        /** @var $classGenerator ClassGenerator */
        $classGenerator->setExtendedClass('AbstractHelper');
        $classGenerator->addUse('Magento\Framework\App\Helper\AbstractHelper');

        $classGenerator->setName($classNameToGenerate);

        $fileGenerator = FileGenerator::fromArray(
            [
                'classes' => [$classGenerator],
            ]
        );

        $directoryWriter = $this->getCurrentModuleDirectoryWriter();
        $directoryWriter->writeFile($filePathToGenerate, $fileGenerator->generate());

        $output->writeln('<info>generated </info><comment>' . $filePathToGenerate . '</comment>');
    }
}
