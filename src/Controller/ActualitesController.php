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
        $donnees = $this->getDoctrine()->getRepository(Actualites::class)->findAll();
        $actualites = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('actualites/index.html.twig', array('actualites' => $actualites));
    }
}
