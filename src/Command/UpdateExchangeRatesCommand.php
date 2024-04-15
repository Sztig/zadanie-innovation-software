<?php

namespace App\Command;

use App\Service\CurrencyExchangeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateExchangeRatesCommand extends Command
{
    protected static $defaultName = 'app:update-exchange-rates';

    public function __construct(
        private CurrencyExchangeService $currencyExchangeService
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:update-exchange-rates');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->currencyExchangeService->updateCurrencies();

        $output->writeln('Currency updated successfully!');

        return Command::SUCCESS;
    }
}