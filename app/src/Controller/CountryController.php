<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class CountryController extends AbstractController
{
    #[Route('/countries', name: 'countries')]
    public function index(CountryRepository $countryRepo, PaginatorInterface $paginator, Request $request): Response
    {
        $sort = $request->query->getAlpha('sort'); //well it's up to you bet there is other ways to get request :) Just think - if you need to test this code - how you test would look
        $direction = $request->query->getAlpha('direction');
        $filter = $request->query->getAlpha('filter');
        $data = $countryRepo->getCountries($sort, $direction, $filter);

        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 12);
        $countries = $paginator->paginate($data, $page, $limit);

        return $this->render(
            'country/index.html.twig',
            [
                'countries' => $countries,
            ]
        );
    }
}
