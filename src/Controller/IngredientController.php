<?php

namespace App\Controller;

use App\Entity\Ingredients;
use App\Form\IngredientType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IngredientsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    /**
     * fonction pour afficher les ingrédients
     *
     * @param IngredientsRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientsRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $repo->findAll();
        $ingredients = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page',1),
            10, /*limite par page*/
            ['sortDirectionParameterName' => 'dir']
        );
        return $this->render('ingredient/index.html.twig',[
            'ingredients'=> $ingredients
        ]);
    }

    #[Route('/ingredient/new', name:'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager){

        $ingredient = new Ingredients();
        $form = $this->createForm(IngredientType::class);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash('success','Ingrédient bien enregistré !!!');

            return $this->redirectToRoute('app_ingredient');
        }

            return $this->render('ingredient/new.html.twig', ['form'=>$form->createView()
        ]);
    }

    #[Route('ingredient/edit/{id}', name:'edit', methods: (['GET', 'POST']))]
    public function edit(int $id, Request $request, IngredientsRepository $repo, EntityManagerInterface $manager) : Response {
        $ingredient = $repo->findOneBy(["id"=>$id]);
        $form = $this->createForm(IngredientType::class, $ingredient);
        // dd($ingredient);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

        $this->addFlash('success','Ingrédient bien modifié !!!');

            return $this->redirectToRoute('app_ingredient');
        }
        return $this->render('ingredient/edit.html.twig', [
            'form'=>$form->createView()
        ]);
    }


    #[Route('ingredient/delete/{id}', name:'delete', methods: [('GET')])]

    public function delete(int $id,  IngredientsRepository $repo, EntityManagerInterface $manager) {
        $ingredient = $repo->findOneBy(["id"=>$id]);

        if(!$ingredient) {
            $this->addFlash('success','Ingrédient non trouvé !!!');
            return $this->redirectToRoute('app_ingredient');
        }
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash('success','Ingrédient supprimé !!!');
            return $this->redirectToRoute('app_ingredient');
    }
}