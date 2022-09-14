<?php

namespace App\Controller;

use App\Model\CountryModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AbstractController
{
    private $countryModel;

    public function __construct(CountryModel $countryModel) // What if there would be more than one Data provider , for ex. - now you think about database/cache - Just think about this Controller, how to change so that this code would not care who is providing data :) Think about decorator pattern
    {
        $this->countryModel = $countryModel; // Also, there is another way to get rid of constructor - think about autowiring ;)
    }

    #[Route('/countries', name: 'countries')]
    public function index(Request $request): Response
    {
        $sort = $request->query->getAlpha('sort'); //well it's up to you bet there is other ways to get request :) Just think - if you need to test this code - how you test would look
        $direction = $request->query->getAlpha('direction');
        $filter = $request->query->getAlpha('filter');
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 12);

        $content = $this->countryModel->getCountries($sort, $direction, $filter);

        $pagination = $this->countryModel->paginate($content, $page, $limit); // Ok let's think what would happen if you want to create one more function which returns cities ? You would create one more model which contains another paginate function ? DRY, KISS

        return $this->render(
            'country/index.html.twig',
            [
                'countries' => $pagination, // Bad naming sense :D
            ]
        );
    }
}
