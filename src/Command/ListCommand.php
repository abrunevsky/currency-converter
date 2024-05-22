<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\SupportedCurrenciesProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:convert:list',
    description: 'Shows the list of the supported currencies to convert'
)]
class ListCommand extends Command
{
    public function __construct(
        private readonly SupportedCurrenciesProvider $listProvider,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $styledOutput = new SymfonyStyle($input, $output);
        $styledOutput->title('Supported currencies');

        try {
            $list = $this->listProvider->getList();
        } catch (\RuntimeException $exception) {
            $styledOutput->error($exception->getMessage());

            return self::FAILURE;
        }

        $table = $styledOutput->createTable();
        $table->setHeaders(['ISO', 'Name']);

        foreach ($list as $iso => $name) {
            $table->addRow([$iso, $name]);
        }

        $table->render();

        return self::SUCCESS;
    }
}
