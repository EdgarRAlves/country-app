<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Country;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class CountryController extends AbstractController
{
    #[Route('/countries', name: 'countries')]
    public function index(Country $country, PaginatorInterface $paginator, Request $request): Response
    {
        $sort = $request->query->getAlpha('sort'); //well it's up to you bet there is other ways to get request :) Just think - if you need to test this code - how you test would look
        $direction = $request->query->getAlpha('direction');
        $filter = $request->query->getAlpha('filter');
        $data = $country->getCountries($sort, $direction, $filter);

        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', $this->getParameter('pagination.default_limit'));
        $countries = $paginator->paginate($data, $page, $limit);

        return $this->render(
            'country/index.html.twig',
            [
                'countries' => $countries,
            ]
        );
    }
}
