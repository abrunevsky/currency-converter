<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\DomainException;
use App\Model\Money;
use App\Service\CurrencyConverter;
use App\Service\SupportedCurrenciesProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    private const INTERNAL_ERROR_MESSAGE = 'Something went wrong';

    public function __construct(
        private readonly SupportedCurrenciesProvider $currenciesProvider,
        private readonly CurrencyConverter $converter,
        #[Autowire(param: 'kernel.debug')]
        private readonly bool $isDebug,
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
            'sourceIso' => '\w+',
            'sourceValue' => '(-)?([0-9]*[.])?[0-9]+',
            'targetIso' => '\w+',
        ]
    )]
    public function convert(string $sourceIso, float $sourceValue, string $targetIso): Response
    {
        try {
            $sourceAmount = new Money($sourceValue, $sourceIso);
            $targetAmount = $this->converter->convert($sourceAmount, $targetIso);
        } catch (DomainException $exception) {
            return new JsonResponse(['error' => ['message' => $exception->getMessage()]], 400);
        } catch (\Throwable $exception) {
            $message = $this->isDebug ? $exception->getMessage() : self::INTERNAL_ERROR_MESSAGE;

            return new JsonResponse(['error' => ['message' => $message]], 500);
        }

        return new JsonResponse([
            'source' => [$sourceAmount->currency => $sourceAmount->value],
            'target' => [$targetAmount->currency => $targetAmount->value],
        ], 200);
    }
}
