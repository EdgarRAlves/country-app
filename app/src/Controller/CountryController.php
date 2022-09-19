<?php // Just remembered - each your writen php file should have strict_types defined ;) Please read and play with it to see what difference it does and why it's good practice to use it ;)

namespace App\Controller;

use App\Model\CountryModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
                'countries' => $pagination,
            ]
        );
    }
}
