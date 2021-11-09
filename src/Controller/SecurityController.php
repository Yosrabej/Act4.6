<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Entity\Upload;
use App\Entity\User;
use App\Form\CongeType;
use App\Form\RegistrationType;
use App\Form\UploadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function home()

    {
        return $this->render('security/home.html.twig');
    }
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('security_login');
        }
        return $this->render('security/registration.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }
    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }
    /**
     * @Route("/cooptation", name="cooptation")
     */
    public function cooptation(Request $request)
    {
        $upload = new Upload();
        $form = $this->createForm(UploadType::class, $upload);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $upload->getName();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('upload_directory', $fileName));
            $upload->setName($fileName);
            //  return $this->redirectToRoute('security_login');
        }
        return $this->render('security/cooptation.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/conge", name="conge")
     */
    public function conge(Request $request, EntityManagerInterface $manager, Security $security)
    {
        $manager = $this->getDoctrine()->getManager();
        $username = $security->getUser()->getUsername();
        $user = $manager->getRepository('App:User')->findOneBy(array('username' => $username));
        $conge = new Conge();
        $conge->setUser($user);

        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $conge->setStatut('en cours');
            $manager->persist($conge);
            $manager->flush();
        }

        //   $id = $user->getId();
        //   $conge = $this->getDoctrine()->getRepository(Conge::class)->findBy(array('id', $id));

        return $this->render('security/conge.html.twig', ['form' => $form->createView(), 'conge' => $conge]);
    }
    /** 
     * @Route("/historique", name="historique")
     */
    public function historique()
    {
        $conge = $this->getDoctrine()->getRepository(Conge::class)->findAll();

        return $this->render('Conge/historique.html.twig', ["conge" => $conge]);
    }
    /** 
     * @Route("/historique/valider/{id}", name="valider")
     */
    public function valider(Request $request,  EntityManagerInterface $manager)
    {
        $conge = new Conge();
        //  $conge->date_debut = $request->date_debut;

        $val = 'validé';
        $conge->setStatut($val);
        $manager->persist($conge);
        $manager->flush();


        return $this->render('Conge/historique.html.twig', ["conge" => $conge]);
    }
}
