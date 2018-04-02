<?php
/**
 * Command for partins input file, filter hotels data and output is needed format
 * @author Artem Naumenko (entsupml@gmail.com)
 */

namespace Whotrades\Command;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratorCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName("generate")
            ->addOption("filter", 'f', InputOption::VALUE_OPTIONAL, "Regex to filter classes", '/.*/')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $classes = $this->getClasses($input->getOption('filter'));

        foreach ($classes as $class) {
            $reflection = new \ReflectionClass($class);
            require(__DIR__ . "/../view/class.php");
        }
    }

    private function getClasses(string $filterRegex): array
    {
        $classes = get_declared_classes();
        $result = [];
        foreach ($classes as $class) {
            if (preg_match($filterRegex, $class)) {
                $result[] = $class;
            }
        }

        return $result;
    }
}
