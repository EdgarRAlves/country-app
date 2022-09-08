<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\CountryModel;

class CountryController extends AbstractController
{
    private $countryModel;

    public function __construct(CountryModel $countryModel)
    {
        $this->countryModel = $countryModel;
    }

    #[Route('/countries', name: 'countries')]
    public function index(Request $request): Response
    {
        $sort = $request->query->getAlpha('sort');
        $direction = $request->query->getAlpha('direction');
        $filter = $request->query->getAlpha('filter');
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 12);

        $content = $this->countryModel->getCountries($sort, $direction, $filter);

        $pagination = $this->countryModel->paginate($content, $page, $limit);

        return $this->render(
            'country/index.html.twig',
            [
                'countries' => $pagination
            ]
        );
    }
}
