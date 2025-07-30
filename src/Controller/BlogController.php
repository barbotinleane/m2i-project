<?php

namespace App\Controller;

use App\Service\PageTracker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Comment;
use App\Form\CommentType;

#[Route('/blog', name: 'app_blog_')]
final class BlogController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function blog(PageTracker $pageTracker, Request $request) : Response
    {
        $articles = [
            [
                'slug' => 'les-secrets-de-notre-cuisine',
                'title' => [
                    'fr' => 'Les secrets de notre cuisine',
                    'en' => 'The Secrets of Our Kitchen',
                    'es' => 'Los secretos de nuestra cocina'
                ],
                'image' => 'https://www.arts-et-gastronomie.com/wp-content/uploads/2024/12/BRASSERIE-FRANCOIS_FOUQUIN-17.jpg',
                'content' => 'Découvrez comment nos chefs créent des plats authentiques avec des ingrédients frais et locaux.',
            ],
            [
                'slug' => 'top-des-plats-preferes',
                'title' => [
                    'fr' => 'Top 5 des plats préférés de nos clients',
                    'en' => 'Top 5 Favorite Dishes of Our Customers',
                    'es' => 'Los 5 platos favoritos de nuestros clientes'
                ],
                'image' => 'https://gerersonrestaurant.fr/wp-content/uploads/2021/09/serveuse1.jpeg',
                'content' => 'Voici les plats les plus populaires selon nos clients. Les avez-vous déjà goûtés ?',
            ],
            [
                'slug' => 'accords-mets-et-vins',
                'title' => [
                    'fr' => 'Nos meilleurs accords mets et vins',
                    'en' => 'Our Best Food and Wine Pairings',
                    'es' => 'Nuestras mejores combinaciones de comida y vino'
                ],
                'image' => 'https://www.bao-vins.fr/wp-content/uploads/2021/01/fran-hogan-Lf1uw3_csYo-unsplash-scaled.jpg',
                'content' => 'Apprenez à marier parfaitement vos plats avec nos vins soigneusement sélectionnés.',
            ],
            [
                'slug' => 'portrait-du-chef',
                'title' => [
                    'fr' => 'Portrait de notre chef',
                    'en' => 'Meet Our Chef',
                    'es' => 'Conoce a nuestro chef'
                ],
                'image' => 'https://www.skillandyou.com/_next/image?url=https%3A%2F%2Famazing-book-4391c66fab.media.strapiapp.com%2Fcuisinier_profession_hero_59490361c5.jpg&w=3840&q=75',
                'content' => 'Un entretien exclusif avec notre chef, sa passion pour la cuisine, et ses inspirations.',
            ],
        ];

        $totalNumberViews = $pageTracker->add($request->attributes->get('_route'));

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            'views' => $totalNumberViews,
        ]);
    }

    #[Route('/latest', name: 'post_last', methods: ['GET'])]
    public function latestArticle(): Response
    {
        $article = [
            'slug' => 'portrait-du-chef',
            'title' => [
                'fr' => 'Découvrez notre dernier Burger',
                'en' => 'Discover Our Latest Burger',
                'es' => 'Descubre Nuestra Última Hamburguesa'
            ],
            'image' => 'https://static.cnews.fr/sites/default/files/styles/image_750_422/public/amirali-mirhashemian-jh5xyk4rr3y-unsplash_6527f15d19ffd.jpg?itok=ARXZ3B-4',
            'content' => 'Découvrez notre dernier burger, une explosion de saveurs avec des ingrédients frais et locaux. Ne manquez pas cette expérience culinaire unique !',
        ];

        return $this->render('partials/_article.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{slug}', name: 'post', requirements: ['slug' => '[a-z-]+'])]
    public function elementAccess(Request $request): Response
    {
        $article = [
            'slug' => 'portrait-du-chef',
            'title' => [
                'fr' => 'Portrait de notre chef',
                'en' => 'Meet Our Chef',
                'es' => 'Conoce a nuestro chef'
            ],
            'image' => 'https://www.skillandyou.com/_next/image?url=https%3A%2F%2Famazing-book-4391c66fab.media.strapiapp.com%2Fcuisinier_profession_hero_59490361c5.jpg&w=3840&q=75',
            'content' => 'Un entretien exclusif avec notre chef, sa passion pour la cuisine, et ses inspirations.',
        ];

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre commentaire a été enregistré avec succès !');
            dump($request->getSession());
        }
        
        return $this->render('blog/article.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}', name: 'post_error', methods: ['GET'])]
    public function errorRedirect(): Response
    {
        return new Response("Argument non respecté", 404);
    }
}

