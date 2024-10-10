<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IndexController extends AbstractController
{
   #[Route('/', name: 'home')]
   public function home(EntityManagerInterface $entityManager): Response
   {
       // Récupérer tous les articles de la table article de la BD
       // et les mettre dans le tableau $articles
       $articles = $entityManager->getRepository(Article::class)->findAll();

       return $this->render('articles/index.html.twig', ['articles' => $articles]);
   }







    #[Route('/article/save', name: 'article_save')]
    public function save(EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $article->setNom('Article 1');
        $article->setPrix(20);

        // Persist and flush the entity
        $entityManager->persist($article);
        $entityManager->flush();

        return new Response('Article enregisté avec id ' . $article->getId());
    }










    #[Route('/article/new', name: 'new_article', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();

        // Create the form
        $form = $this->createFormBuilder($article)
            ->add('nom', TextType::class)
            ->add('prix', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Créer'])
            ->getForm();

        // Handle the request
        $form->handleRequest($request);

        // Check if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the data from the form
            $article = $form->getData();

            // Persist the entity and save it in the database
            $entityManager->persist($article);
            $entityManager->flush();

            // Redirect to the article list or any other page
            return $this->redirectToRoute('article_list');
        }

        // Render the form view
        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }










 #[Route('/article/{id}', name: 'article_show')]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        // Fetch the article by ID
        $article = $entityManager->getRepository(Article::class)->find($id);

        // Handle the case when the article is not found (optional)
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        // Render the article view
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }









    #[Route('/article/edit/{id}', name: 'edit_article', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        // Fetch the article by ID
        $article = $entityManager->getRepository(Article::class)->find($id);

        // Handle case when the article is not found
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        // Create the form
        $form = $this->createFormBuilder($article)
            ->add('nom', TextType::class)
            ->add('prix', TextType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Modifier'
            ])
            ->getForm();

        // Handle the request
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes and flush the entity
            $entityManager->persist($article);
            $entityManager->flush();

            // Redirect to the article list
            return $this->redirectToRoute('article_list');
        }

        // Render the form view
        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/delete/{id}', name: 'delete_article', methods: ['DELETE'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        // Fetch the article by ID
        $article = $entityManager->getRepository(Article::class)->find($id);

        // Handle case when the article is not found
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        // Remove the article
        $entityManager->remove($article);
        $entityManager->flush();

        // Return a response (JSON or redirect)
        return $this->redirectToRoute('article_list');
    }








}
