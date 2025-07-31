<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\PageTracker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/blog', name: 'app_blog_')]
final class BlogController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function blog(PageTracker $pageTracker, Request $request, ArticleRepository $articleRepository) : Response
    {
        $articles = $articleRepository->findAll();

        $totalNumberViews = $pageTracker->add($request->attributes->get('_route'));

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            'views' => $totalNumberViews,
        ]);
    }

    #[Route('/latest', name: 'post_last', methods: ['GET'])]
    public function latestArticle(ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->findOneBy([], ['id' => 'DESC']);

        return $this->render('partials/_article.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}', name: 'post', requirements: ['slug' => '[a-z-]+'])]
    public function elementAccess(Request $request, EntityManagerInterface $entityManager, Article $article): Response
    {
        $comments = $article->getComment();

        $comment = new Comment();
        $comment->setArticle($article);
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre commentaire a été enregistré avec succès !');
            
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        
        return $this->render('blog/article.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'comments' => $comments,
        ]);
    }

    #[Route('/{slug}', name: 'post_error', methods: ['GET'])]
    public function errorRedirect(): Response
    {
        return new Response("Argument non respecté", 404);
    }
}

