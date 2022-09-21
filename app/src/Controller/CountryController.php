<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CountryRepository;
use App\Trait\PaginateTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class CountryController extends AbstractController
{
    use PaginateTrait;

    public function __construct(
        private CountryRepository $countryRepo,
        private PaginatorInterface $paginator
    ) {} /*What if there would be more than one Data provider , for ex. - now you think about database/cache - Just think about this Controller, how to change so that this code would not care who is providing data :) Think about decorator pattern
    Also, there is another way to get rid of constructor - think about autowiring ;)*/

    #[Route('/countries', name: 'countries')]
    public function index(Request $request): Response
    {
        $sort = $request->query->getAlpha('sort'); //well it's up to you bet there is other ways to get request :) Just think - if you need to test this code - how you test would look
        $direction = $request->query->getAlpha('direction');
        $filter = $request->query->getAlpha('filter');
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 12);

        $content = $this->countryRepo->getCountries($sort, $direction, $filter);

        $pagination = $this->paginate($content, $page, $limit);

        return $this->render(
            'country/index.html.twig',
            [
                'countries' => $pagination, // Bad naming sense :D
            ]
        );
    }
}
