<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Money;
use App\Service\CurrencyConverter;
use App\Service\SupportedCurrenciesProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly SupportedCurrenciesProvider $currenciesProvider,
        private readonly CurrencyConverter $converter,
    ) {
    }

    #[Route('/')]
    public function index(): Response
    {
        return new RedirectResponse('/currencies');
    }

    #[Route('/currencies')]
    public function currencies(): Response
    {
        return new JsonResponse($this->currenciesProvider->getList(), 200);
    }

    #[Route(
        '/convert/{sourceIso}/{sourceValue}/{targetIso}',
        requirements: [
            'sourceIso' => '[a-zA-Z]{3}',
            'sourceValue' => '([0-9]*[.])?[0-9]+',
            'targetIso' => '[a-zA-Z]{3}',
        ]
    )]
    public function convert(string $sourceIso, float $sourceValue, string $targetIso): Response
    {
        $sourceAmount = new Money($sourceValue, $sourceIso);

        try {
            $targetAmount = $this->converter->convert($sourceAmount, $targetIso);
        } catch (\RuntimeException $exception) {
            return new JsonResponse([
                'error' => [
                    'message' => $exception->getMessage(),
                ],
            ], 400);
        }

        return new JsonResponse([
            'source' => [
                $sourceAmount->iso => $sourceAmount->value,
            ],
            'target' => [
                $targetAmount->iso => $targetAmount->value,
            ],
        ], 200);
    }
}
