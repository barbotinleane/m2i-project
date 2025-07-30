<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\PageTracker;
use Symfony\Component\HttpFoundation\Request;

final class MenuController extends AbstractController
{
    #[Route(path: '/menu', name: 'app_menu', priority: -1)]
    public function menu(Request $request, PageTracker $pageTracker): Response
    {
        $menu = [
            [
                'slug' => 'salade-nicoise',
                'title' => [
                    'fr' => 'Salade Niçoise',
                    'en' => 'Niçoise Salad',
                    'es' => 'Ensalada Niçoise'
                ],
                'image' => 'https://img.cuisineaz.com/1024x768/2013/12/20/i34581-salade-nicoise-rapide.jpeg',
                'description' => 'Une salade fraîche avec du thon, des œufs, des olives et des légumes de saison.',
                'price' => 12.90,
                'category' => 'Entrée',
            ],
            [
                'slug' => 'soupe-oignon',
                'title' => [
                    'fr' => 'Soupe à l’oignon',
                    'en' => 'French Onion Soup',
                    'es' => 'Sopa de cebolla'
                ],
                'image' => 'https://assets.afcdn.com/recipe/20210104/116953_w1024h1024c1cx806cy863cxt0cyt382cxb1641cyb1350.jpg',
                'description' => 'Un classique gratiné au fromage avec des croûtons dorés.',
                'price' => 8.50,
                'category' => 'Entrée',
            ],
            [
                'slug' => 'boeuf-bourguignon',
                'title' => [
                    'fr' => 'Bœuf Bourguignon',
                    'en' => 'Beef Bourguignon',
                    'es' => 'Boeuf Bourguignon'
                ],
                'image' => 'https://i0.wp.com/temps-de-cuisson.fr/wp-content/uploads/2024/12/boeuf-bourguignon.webp?fit=1500%2C1000&ssl=1',
                'description' => 'Ragoût de bœuf mijoté au vin rouge avec carottes, oignons et champignons.',
                'price' => 18.00,
                'category' => 'Plat',
            ],
            [
                'slug' => 'filet-saumon',
                'title' => [
                    'fr' => 'Filet de saumon',
                    'en' => 'Salmon Fillet',
                    'es' => 'Filete de salmón'
                ],
                'image' => 'https://img.cuisineaz.com/1280x720/2014/02/24/i78436-filet-de-saumon-au-four.webp',
                'description' => 'Filet de saumon grillé servi avec légumes vapeur et sauce citronnée.',
                'price' => 17.50,
                'category' => 'Plat',
            ],
            [
                'slug' => 'tarte-citron',
                'title' => [
                    'fr' => 'Tarte au citron meringuée',
                    'en' => 'Lemon Meringue Pie',
                    'es' => 'Tarta de limón con merengue'
                ],
                'image' => 'https://cdn.pratico-pratiques.com/app/uploads/sites/3/2019/05/14090053/tarte-au-citron.jpg',
                'description' => 'Dessert acidulé avec une meringue légère et fondante.',
                'price' => 6.00,
                'category' => 'Dessert',
            ],
        ];

        $totalNumberViews = $pageTracker->add($request->attributes->get('_route'));

        return $this->render('menu/index.html.twig', [
            'menu' => $menu,
            'views' => $totalNumberViews,
        ]);
    }
}
