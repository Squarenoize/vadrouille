<?php
/**
 * Helper SEO - Centralise la génération des données SEO et Schema.org
 */
class SeoHelper {
    
    private const SITE_URL = 'https://vadbou.fr';
    private const SITE_NAME = 'Vadrouille & Bourlingue';
    private const SITE_EMAIL = 'vadrouillebourlingue@gmail.com';
    private const INSTAGRAM_URL = 'https://www.instagram.com/vadrouillebourlingue/';
    
    /**
     * Configuration SEO par page
     */
    private const SEO_CONFIG = [
        'home' => [
            'title' => 'Vadrouille & Bourlingue - Voyages sur mesure organisés par des passionnés',
            'description' => 'Découvrez nos voyages sur mesure créés par des experts. Un parcours fluide vers l\'exceptionnel : brief, création, ajustement et départ en toute sérénité.',
            'url' => '',
            'image' => 'assets/img/home_front.png'
        ],
        'trips' => [
            'title' => 'Nos Voyages Réalisés | Vadrouille & Bourlingue',
            'description' => 'Découvrez nos voyages soigneusement élaborés à travers le monde. Aventure, détente, culture ou week-end : nous construirons le voyage parfait pour vous.',
            'url' => 'voyages',
            'image' => 'assets/img/trips/Edimbourgh.jpg'
        ],
        'contact' => [
            'title' => 'Contactez-nous - Demandez votre voyage | Vadrouille & Bourlingue',
            'description' => 'Partagez vos aspirations avec nous. Que ce soit une quête de sérénité ou une soif d\'aventure, nous façonnons chaque détail pour une expérience sur mesure.',
            'url' => 'contact',
            'image' => 'assets/img/rockStele.png'
        ],
        'about' => [
            'title' => 'À propos - Notre philosophie | Vadrouille & Bourlingue',
            'description' => 'L\'art de s\'égarer pour mieux se retrouver. Découvrez notre philosophie, notre passion pour les cultures lointaines et notre volonté de proposer des voyages authentiques.',
            'url' => 'a-propos',
            'image' => 'assets/img/Travel_Planner_Portrait.png'
        ],
        'terms' => [
            'title' => 'Mentions légales | Vadrouille & Bourlingue',
            'description' => 'Consultez les mentions légales et conditions générales d\'utilisation de Vadrouille & Bourlingue, votre agence de voyages sur mesure.',
            'url' => 'mentions-legales',
            'image' => 'assets/img/VB_logo_hori.png'
        ]
    ];
    
    /**
     * Noms des pages pour breadcrumbs
     */
    private const PAGE_NAMES = [
        'home' => 'Accueil',
        'trips' => 'Voyages',
        'contact' => 'Contact',
        'about' => 'À propos',
        'terms' => 'Mentions légales'
    ];
    
    /**
     * Récupérer les données SEO de base pour une page
     */
    public static function getPageSeo(string $page): array {
        $config = self::SEO_CONFIG[$page] ?? self::SEO_CONFIG['home'];
        
        return [
            'pageTitle' => $config['title'],
            'pageDescription' => $config['description'],
            'pageFullUrl' => self::SITE_URL . '/' . $config['url'],
            'pageFullImage' => self::SITE_URL . '/' . $config['image']
        ];
    }
    
    /**
     * Générer le Schema.org de l'organisation (identique pour toutes les pages)
     */
    public static function getOrganizationSchema(): array {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'TravelPlanner',
            'name' => self::SITE_NAME,
            'description' => 'Planificateur de voyages sur mesure spécialisé dans la création d\'expériences de voyage authentiques et personnalisées.',
            'url' => self::SITE_URL,
            'logo' => self::SITE_URL . '/assets/img/VB_logo_hori.png',
            'image' => self::SITE_URL . '/assets/img/VB_logo_hori.png',
            'email' => self::SITE_EMAIL,
            'sameAs' => [
                self::INSTAGRAM_URL
            ],
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'FR'
            ],
            'priceRange' => '€€',
            'areaServed' => [
                '@type' => 'Place',
                'name' => 'Worldwide'
            ]
        ];
    }
    
    /**
     * Générer le fil d'Ariane (Breadcrumb)
     */
    public static function getBreadcrumbs(string $page): array {
        $breadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Accueil',
                    'item' => self::SITE_URL . '/'
                ]
            ]
        ];
        
        // Ajouter la page actuelle si ce n'est pas l'accueil
        if ($page !== 'home') {
            $config = self::SEO_CONFIG[$page] ?? null;
            $breadcrumbs['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => self::PAGE_NAMES[$page] ?? ucfirst($page),
                'item' => self::SITE_URL . '/' . ($config['url'] ?? '')
            ];
        }
        
        return $breadcrumbs;
    }
    
    /**
     * Générer le Schema Service pour la page d'accueil
     */
    public static function getHomeServiceSchema(): array {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => 'Conception de voyages sur mesure',
            'description' => 'Service de planification et organisation de voyages personnalisés avec accompagnement d\'un expert du voyage.',
            'provider' => [
                '@type' => 'TravelPlanner',
                'name' => self::SITE_NAME
            ],
            'serviceType' => 'Travel Planning',
            'areaServed' => 'Worldwide',
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Voyages sur mesure',
                'itemListElement' => [
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'Voyage culturel',
                            'description' => 'Immersion culturelle et découverte du patrimoine'
                        ]
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'Voyage d\'aventure',
                            'description' => 'Expériences sportives et exploration'
                        ]
                    ],
                    [
                        '@type' => 'Offer',
                        'itemOffered' => [
                            '@type' => 'Service',
                            'name' => 'Voyage détente',
                            'description' => 'Relaxation et bien-être'
                        ]
                    ]
                ]
            ]
        ];
    }
    
    /**
     * Générer le Schema ItemList pour la page voyages
     */
    public static function getTripsSchema(): array {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Voyages réalisés par ' . self::SITE_NAME,
            'description' => 'Portfolio de voyages sur mesure organisés',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@type' => 'TouristTrip',
                        'name' => 'Escapade culturelle à Édimbourg',
                        'description' => 'Découverte du patrimoine écossais',
                        'image' => self::SITE_URL . '/assets/img/trips/Edimbourgh.jpg',
                        'touristType' => 'Cultural'
                    ]
                ]
            ]
        ];
    }
    
    /**
     * Générer le Schema AboutPage
     */
    public static function getAboutSchema(): array {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'AboutPage',
            'name' => 'À propos de ' . self::SITE_NAME,
            'description' => 'Notre philosophie : l\'art de s\'égarer pour mieux se retrouver',
            'mainEntity' => [
                '@type' => 'Person',
                'name' => 'Travel Planner',
                'jobTitle' => 'Expert voyage et créateur d\'expériences',
                'description' => 'Passionnée par les cultures lointaines avec plus de deux décennies d\'exploration',
                'image' => self::SITE_URL . '/assets/img/Travel_Planner_Portrait.png',
                'worksFor' => [
                    '@type' => 'TravelPlanner',
                    'name' => self::SITE_NAME
                ]
            ]
        ];
    }
    
    /**
     * Générer le Schema ContactPage
     */
    public static function getContactSchema(): array {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'name' => 'Contactez ' . self::SITE_NAME,
            'description' => 'Demandez votre voyage sur mesure'
        ];
    }
    
    /**
     * Charger le Schema FAQ (spécifique à la page home)
     */
    public static function getFaqSchema(): array {
        return include __DIR__ . '/../views/schemas/faq-schema.php';
    }
}
