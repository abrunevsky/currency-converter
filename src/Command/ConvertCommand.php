<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Money;
use App\Service\CurrencyConverter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:convert',
    description: 'Converts an amount of money from one currency to another'
)]
class ConvertCommand extends Command
{
    private const SOURCE_AMOUNT = 'source-amount';
    private const SOURCE_CURRENCY_ISO = 'source-iso';
    private const TARGET_CURRENCY_ISO = 'target-iso';

    public function __construct(
        private readonly CurrencyConverter $converter,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument(self::SOURCE_CURRENCY_ISO, InputArgument::REQUIRED, 'Source currency ISO code')
            ->addArgument(self::SOURCE_AMOUNT, InputArgument::REQUIRED, 'Source amount of money')
            ->addArgument(self::TARGET_CURRENCY_ISO, InputArgument::REQUIRED, 'Target currency ISO code')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $styledOutput = new SymfonyStyle($input, $output);
        $styledOutput->title('Converter currency');

        $sourceAmount = new Money(
            (float) $input->getArgument(self::SOURCE_AMOUNT),
            $input->getArgument(self::SOURCE_CURRENCY_ISO)
        );

        try {
            $targetAmount = $this->converter->convert($sourceAmount, $input->getArgument(self::TARGET_CURRENCY_ISO));
        } catch (\RuntimeException $exception) {
            $styledOutput->error($exception->getMessage());

            return self::FAILURE;
        }

        $table = $styledOutput->createTable();
        $table->setHeaders(['Source', 'Target']);
        $table->setRows([
            [
                self::formatMoney($sourceAmount),
                self::formatMoney($targetAmount),
            ],
        ]);
        $table->render();

        return self::SUCCESS;
    }

    private static function formatMoney(Money $money): string
    {
        return sprintf('%s %.2f', $money->iso, $money->value);
    }
}
