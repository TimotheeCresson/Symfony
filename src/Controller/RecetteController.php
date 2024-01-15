<?php

namespace App\Controller;

use App\Entity\Recettes;
use App\Form\RecetteType;
use App\Repository\RecettesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'app_recette')]
    public function index(RecettesRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $recettes = $repo->findAll();
        $recettes = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page',1),
            10,
            ['sortDirectionParameterName' => 'dir']
        );
        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes
        ]);
    }


#[Route('/recette/newRecette', name:'newRecette', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager){

        $recette = new Recettes();
        $form = $this->createForm(RecetteType::class);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash('success','Recette bien enregistré !!!');

            return $this->redirectToRoute('app_recette');
        }

            return $this->render('recette/newRecette.html.twig', ['form'=>$form->createView()
        ]);
    }

    #[Route('/recette/editRecette/{id}', name:'editRecette', methods: (['GET', 'POST']))]
    public function edit(int $id, Request $request, RecettesRepository $repo, EntityManagerInterface $manager) : Response {
        $recette = $repo->findOneBy(["id"=>$id]);
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

        $this->addFlash('success','Recette bien modifié !!!');

            return $this->redirectToRoute('app_recette');
        }
        return $this->render('recette/editRecette.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    #[Route('/recette/deleteRecette/{id}', name:'deleteRecette', methods: [('GET')])]

    public function delete(int $id,  RecettesRepository $repo, EntityManagerInterface $manager) {
        $recette = $repo->findOneBy(["id"=>$id]);

        if(!$recette) {
            $this->addFlash('success','Recette non trouvé !!!');
            return $this->redirectToRoute('app_recette');
        }
        $manager->remove($recette);
        $manager->flush();

        $this->addFlash('success','Recette supprimé !!!');
            return $this->redirectToRoute('app_recette');
    }
}