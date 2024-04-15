<?php

namespace App\Service;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyExchangeService
{
    private string $url = 'https://api.nbp.pl/api/exchangerates/tables/a/';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private HttpClientInterface    $client
    )
    {
    }

    public function getCurrencies(): array
    {
        return $this->entityManager->getRepository(Currency::class)->findAll();
    }

    public function updateCurrencies(): void
    {
        $data = $this->getDataFromResponse();

        if ($data) {
            $this->updateStoredCurrencies($data);
        }
    }

    private function getDataFromResponse(): array
    {
        $response = $this->client->request('GET', $this->url, [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);

        return $response->toArray();
    }

    private function updateStoredCurrencies(array $currencyData): void
    {
        foreach ($currencyData[0]['rates'] as $exchangeRate) {
            $currency = $this->entityManager->getRepository(Currency::class)->findOneBy([
                'currency_code' => $exchangeRate['code']
            ]);

            if ($currency) {
                $currency->setExchangeRate($exchangeRate['mid']);
            } else {
                $currency = new Currency();
                $currency->setName($exchangeRate['currency']);
                $currency->setExchangeRate((float)$exchangeRate['mid']);
                $currency->setCurrencyCode($exchangeRate['code']);
                $this->entityManager->persist($currency);
            }
        }

        $this->entityManager->flush();
    }
}