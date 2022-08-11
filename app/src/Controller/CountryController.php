<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CountryController extends AbstractController
{
    private $client;
    private $paginator;

    public function __construct(HttpClientInterface $client, PaginatorInterface $paginator)
    {
        $this->client = $client;
        $this->paginator = $paginator;
    }

    #[Route('/countries', name: 'countries')]
    public function index(Request $request): Response
    {
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/all?fields=name,population,region'
        );
        $content = $response->toArray();

        // sorting by population
        if ($request->query->getAlpha('sort') == 'population') {
            if ($request->query->getAlpha('direction') == 'desc') {
                usort($content, function ($a, $b){
                    return $b['population'] <=> $a['population'];
                });
            } else {
                usort($content, function ($a, $b){
                    return $a['population'] <=> $b['population'];
                });
            }
        }

        // sorting by region
        if ($request->query->getAlpha('sort') == 'region') {
            if ($request->query->getAlpha('direction') == 'desc') {
                usort($content, function ($a, $b){
                    return $b['region'] <=> $a['region'];
                });
            } else {
                usort($content, function ($a, $b){
                    return $a['region'] <=> $b['region'];
                });
            }
        }

        // pagination
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
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/region/Europe?fields=name,population,region'
        );
        $content = $response->toArray();

        if ($request->query->getAlpha('sort') == 'population') {
            if ($request->query->getAlpha('direction') == 'desc') {
                usort($content, function ($a, $b){
                    return $b['population'] <=> $a['population'];
                });
            } else {
                usort($content, function ($a, $b){
                    return $a['population'] <=> $b['population'];
                });
            }
        }

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
        $response = $this->client->request(
            'GET',
            'https://restcountries.com/v2/all?fields=name,population,region'
        );

        $countries = $response->toArray();
        $countryNames = array_column($countries, 'name');
        $lithuania = array_search('Lithuania', $countryNames);
        $lithuaniaPopulation = $countries[$lithuania]['population'];

        $result = [];

        foreach ($countries as $country) {
            if ($country['population'] < $lithuaniaPopulation) {
                $result[] = $country;
            }
        }

        if ($request->query->getAlpha('sort') == 'population') {
            if ($request->query->getAlpha('direction') == 'desc') {
                usort($result, function ($a, $b){
                    return $b['population'] <=> $a['population'];
                });
            } else {
                usort($result, function ($a, $b){
                    return $a['population'] <=> $b['population'];
                });
            }
        }

        $pagination = $this->paginator->paginate(
            $result,
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
