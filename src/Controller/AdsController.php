<?php

namespace App\Controller;

use App\Service\JobijobaClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdsController extends AbstractController {
    private $jobijobaService;

    public function __construct(JobijobaClient $jobijobaService) {
        $this->jobijobaService = $jobijobaService;
    }

    #[Route('/', name: 'ads_list')]
    public function index(): Response {
        $ads = $this->jobijobaService->getJobs();
        
        return new Response(json_encode($ads));
    }
}