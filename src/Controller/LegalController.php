<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    #[Route('/conditions-generales-de-vente', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('legal/cgv.html.twig');
    }

    #[Route('/politique-de-confidentialite', name: 'app_confidentialite')]
    public function confidentialite(): Response
    {
        return $this->render('legal/pdc.html.twig');
    }

    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('legal/ml.html.twig');
    }
}

?>