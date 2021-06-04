<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\CSV;
use App\Repository\CSVRepository;

class ClientController extends AbstractController
{
    public function index(Request $request): Response
    {
        return $this->render('csvviewer/index.html.twig');
    }
}
