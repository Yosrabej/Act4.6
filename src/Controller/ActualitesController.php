<?php

namespace App\Controller;

use App\Entity\Actualites;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ActualitesController extends AbstractController
{
    /**
     * @Route("/actualites", name="actualites")
     */
    public function index(Request $request, PaginatorInterface $paginator)

    {
        // $actualites = new Actualites();
        $donnees = $this->getDoctrine()->getRepository(Actualites::class)->findAll();
        $actualites = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        return $this->render('actualites/index.html.twig', array('actualites' => $actualites));
    }
}
