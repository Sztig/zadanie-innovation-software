<?php

namespace App\Controller;

use App\Service\CurrencyExchangeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CurrencyExchangeController extends AbstractController
{
    public function __construct(
        private CurrencyExchangeService $currencyExchangeService
    )
    {
    }

    #[Route('/', name: 'app_currency', methods: ['GET'])]
    public function index(): Response
    {
        $currencies = $this->currencyExchangeService->getCurrencies();

        return $this->render('currency_exchange/index.html.twig', [
            'currencies' => $currencies,
        ]);
    }

    #[Route('/update', name: 'app_currency_update', methods: ['POST'])]
    public function update(): Response
    {
        $this->currencyExchangeService->updateCurrencies();

        return $this->redirectToRoute('app_currency');
    }
}
