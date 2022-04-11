<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\Livreur;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LivraisonController extends AbstractController
{
    /**
     * @Route("/indexliv", name="indexliv", methods={"GET"})
     */
    public function indexliv(LivraisonRepository $repository,EntityManagerInterface $entityManager): Response
    {
        $livraisons = $repository->Search();
            $livreur = $entityManager
            ->getRepository(Livreur::class)
            ->findAll();

        return $this->render('Delivery.html.twig', [
            'livraisons' => $livraisons,
            'livreurs' => $livreur
        ]);
    }
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
    
        return $this->render('index.html.twig');
    }


    /**
     * @Route("/admin", name=" ", methods={"GET"})
     */
    public function indexAdmin(EntityManagerInterface $entityManager): Response
    {
        

        return $this->render('Livraison/index.html.twig');
    }

    /**
     * @Route("/indexliv/add", name="addLiv", methods={"GET" , "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->add('Add Delivery', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('indexliv', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('DeliveryAdd.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }

        


    /**
     * @Route("show/{idlivraison}", name="app_livraison_show", methods={"GET"})
     */
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }
 /**
     * @Route("/edit/{id}", name="edit" ,methods={"GET", "POST"})
     */
   
   
    public function edit(Request $request, $id): Response
 
    {   
        $livraison = $this->getDoctrine()->getRepository(Livraison::class)->find($id);
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->add('Update Delivery', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush($livraison);

            return $this->redirectToRoute('indexliv');
        }

        
            return $this->render('Livraison/edit.html.twig', [
                'livraison' => $livraison,
                'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idlivraison}", name="deletel1", methods={"POST"})
     */
    public function delete(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getIdlivraison(), $request->request->get('_token'))) {
            $entityManager->remove($livraison);
            $entityManager->flush();
        }

        return $this->redirectToRoute('indexliv', [], Response::HTTP_SEE_OTHER);
    }
   /**
     *  @Route("/Deletel/{id}", name="Deletel")
     */
    public function Deletel($id)
    {
        
        $livraison= $this->getDoctrine()->getRepository(Livraison::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($livraison);
        $em->flush();
        return $this->redirectToRoute("indexliv");
    }
    /**
     * @param StockRepository $repository
     * @param Request $request
     * @return Response
     *  @Route("/livraison/SearchName", name="SEARCH")
     */
    function SearchName(LivraisonRepository $repository,Request $request, EntityManagerInterface $entityManager)
    {
        $name = $request->get('search');
        $livraisons = $repository->SearchName($name);
        $livreur = $entityManager
        ->getRepository(Livreur::class)
        ->findAll();

    return $this->render('Delivery.html.twig', [
        'livraisons' => $livraisons,
        'livreurs' => $livreur
    ]);
}
}


