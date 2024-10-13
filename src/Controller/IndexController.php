<?php
namespace App\Controller;
use App\Form\ArticleType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Category;
use App\Form\CategoryType;


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
        // Create a new article instance
        $article = new Article();

        // Create the form using the ArticleType
        $form = $this->createForm(ArticleType::class, $article);

        // Handle the request
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the new article
            $entityManager->persist($article);
            $entityManager->flush();

            // Redirect to the article list
            return $this->redirectToRoute('home');
        }

        // Render the form view
        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
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
        $form = $this->createForm(ArticleType::class, $article);

        // Handle the request
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes (not necessary to call persist here as $article is already managed)
            $entityManager->flush();

            // Redirect to the article list
            return $this->redirectToRoute('home');
        }

        // Render the form view
        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }









    











    #[Route('/article/delete/{id}', name: 'delete_article', methods: ['POST'])]
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

    // Redirect to the article list after deletion
    return $this->redirectToRoute('home');
}


#[Route('/article/{id}', name: 'article_show')]
public function show(int $id, EntityManagerInterface $entityManager): Response
{
    $article = $entityManager->getRepository(Article::class)->find($id);

    if (!$article) {
        throw $this->createNotFoundException('Article not found');
    }

    return $this->render('articles/show.html.twig', [
        'article' => $article,
    ]);
}




#[Route('/category/newCat', name: 'new_category', methods: ['GET', 'POST'])]
    public function newCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            // Optionally, redirect to a different page after success
            // return $this->redirectToRoute('category_list');
            // Redirect to the article list
            return $this->redirectToRoute('home');
        }

        return $this->render('articles/newCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }





}
