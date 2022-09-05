<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\CountryModel;
use Knp\Component\Pager\PaginatorInterface;

class CountryController extends AbstractController
{
    private $countryModel;
    private $paginator;

    public function __construct(CountryModel $countryModel, PaginatorInterface $paginator)
    {
        $this->countryModel = $countryModel;
        $this->paginator = $paginator;
    }

    #[Route('/countries', name: 'countries')]
    public function index(Request $request): Response
    {
        $sort = $request->query->getAlpha('sort');
        $direction = $request->query->getAlpha('direction');
        $filter = ['all'];

        $content = $this->countryModel->getCountries($sort, $direction, $filter);

        $pagination = $this->paginator->paginate(
            $content,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 12)
        );

        return $this->render(
            'country/index.html.twig',
            [
                'countries' => $pagination
            ]
        );
    }

    #[Route('/countries/Europe', name: 'europe')]
    public function countriesFromEurope(Request $request): Response
    {
        $sort = $request->query->getAlpha('sort');
        $direction = $request->query->getAlpha('direction');
        $filter = ['europe'];

        $content = $this->countryModel->getCountries($sort, $direction, $filter);

        $pagination = $this->paginator->paginate(
            $content,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 12)
        );

        return $this->render(
            'country/europe.html.twig',
            [
                'countries' => $pagination
            ]
        );
    }

    #[Route('/countries/smallerThanLithuania', name: 'smaller_lithuania')]
    public function countriesSmallerThanLithuania(Request $request): Response
    {
        $sort = $request->query->getAlpha('sort');
        $direction = $request->query->getAlpha('direction');
        $filter = ['all', 'smallerThanLithuania'];

        $content = $this->countryModel->getCountries($sort, $direction, $filter);

        $pagination = $this->paginator->paginate(
            $content,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 12)
        );

        return $this->render(
            'country/smaller_lithuania.html.twig',
            [
                'countries' => $pagination
            ]
        );
    }
}
