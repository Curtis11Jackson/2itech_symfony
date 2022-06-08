<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        //$articles = $articleRepository->findAll();
        
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
            'articles' =>$articleRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

        /**
     * @Route("/show/{id}", name="show")
     */
    #[Route('/show/{id}', name: 'show')]
    public function show(ArticleRepository $articleRepository, $id): Response
    {
        $article = $articleRepository->find($id);

        // dd($article);
        
        // test si l'article n'est pas un number ou null
        if (!$article) {
            return $this->redirectToRoute('home');
        }

        return $this->render('show/show.html.twig', [
            'controller_name' => 'HomeController',
            'article' => $article,
        ]);
    }

    #[Route('/showArticleCategory/{id}', name: 'show_Article_Category')]
    public function showArticleCategory(?Category $categorie, CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();

        if($categorie) {

            $articles = $categorie->getArticles()->getValues();
            

        }else {

            return $this->redirectToRoute('home');
        }

        // dd($articles);

        return $this->render("home/home.html.twig", [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }
}
