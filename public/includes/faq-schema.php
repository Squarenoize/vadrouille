<?php
/**
 * FAQ Schema.org pour Vadrouille & Bourlingue
 * Utilisé pour afficher une FAQ en accordéon dans les résultats Google
 */

$faqSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => [
        [
            '@type' => 'Question',
            'name' => 'Comment fonctionne la création d\'un voyage sur mesure ?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Notre processus se déroule en 4 étapes : 1) Préparation - vous remplissez notre formulaire en ligne pour partager vos envies et nous vous soumettons un premier devis dans les 48h. 2) La Création - Une fois votre devis accepté,votre expert vous dessine un itinéraire exclusif et vous donne accès à votre espace personnel. 3) L\'Ajustement - nous affinons ensemble chaque détail par messagerie exclusive et vous suivez votre processus de réservations. 4) Le Départ - nous restons en contact tout au long de votre voyage.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Quel est le délai pour organiser un voyage sur mesure ?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Nous recommandons de nous contacter au minimum 2 à 3 mois avant votre départ souhaité pour garantir la disponibilité des meilleures options. Pour les destinations lointaines ou les périodes de haute saison, un délai de 4 à 6 mois est conseillé.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Quelles destinations proposez-vous ?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Nous concevons des voyages sur mesure partout dans le monde. Notre expertise principale se situe principalement en Europe, au Canada et au Japon. Chaque destination est sélectionnée pour son authenticité et ses expériences uniques.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Proposez-vous des voyages pour tous les budgets ?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Nous créons des voyages personnalisés accessibles à tous. Nous adaptons nos propositions selon vos contraintes budgétaires tout en maintenant notre standard de qualité.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Qui réserve les vols et hébergements ?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Nous concevons votre voyage de A à Z et vous donnons accès à un espace personnel avec toutes les recommandations. Vous effectuez ensuite vos réservations vous-même, ce qui vous garantit flexibilité et contrôle. Nous restons disponibles pour vous accompagner à chaque étape.'
            ]
        ],
        [
            '@type' => 'Question',
            'name' => 'Que se passe-t-il pendant mon voyage ?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Nous restons en contact tout au long de votre voyage via notre messagerie pour nous assurer que tout se passe bien. Vous disposez d\'un carnet de voyage détaillé avec tous les contacts, réservations et conseils pratiques. Notre expert vous accompagne du départ jusqu\'au retour.'
            ]
        ]
    ]
];

return $faqSchema;
