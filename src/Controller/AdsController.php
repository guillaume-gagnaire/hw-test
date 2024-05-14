<?php

namespace App\Controller;

use App\Service\JobijobaClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AdsController extends AbstractController {
    private $jobijobaService;

    public function __construct(JobijobaClient $jobijobaService) {
        $this->jobijobaService = $jobijobaService;
    }

    #[Route('/', name: 'ads_list')]
    public function index(Request $request): Response {
        $query = $request->query->get('q') ?? '';
        $page = intval($request->query->get('page') ?? 1);
        $limit = 10;
        $ads = $this->jobijobaService->getJobs(
            $query,
            $page,
            $limit,
            'fr'
        );
        $maxPage = ceil($ads['total'] / $limit);
        
        return $this->render('list.html.twig', [
            'query' => $query,
            'ads' => $ads['ads'],
            'total' => $ads['total'],
            'page' => $page,
            'limit' => $limit,
            'maxPage' => $maxPage,
            'prevPage' => $page <= 1 ? null : '/?' . http_build_query([
                'q' => $query,
                'page' => $page - 1
            ]),
            'nextPage' => $page >= $maxPage ? null : '/?' . http_build_query([
                'q' => $query,
                'page' => $page + 1
            ])
        ]);
    }
}