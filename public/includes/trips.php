<?php
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
} else {
    $filter = 'all';
}
$backendReady = false;
?>
    <section class="trips">
        <div class="trips-header">
            <h1>Nos Voyages <span>réalisés</span>.</h1>
            <p>Découvrez nos voyages soigneusement élaborés pour vous inspirer d'expériences inoubliables vécuesà travers le monde. Que vous soyez à la recherche d'aventure, de détente, de culture ou de week-end, nous construirons le voyage parfait pour vous.</p>
            <?php if ($backendReady) { ?>
            <div class="trips-filters">
                <span class="filters-label">Filtrer par :</span>
                <div class="filters-buttons">
                    <button class="filter-btn <?php if($filter == 'all') echo 'active'; ?>" onclick="window.location.href='<?php echo BASE_URL; ?>/voyages?filter=all'">Tous</button>
                    <button class="filter-btn <?php if($filter == 'adventure') echo 'active'; ?>" onclick="window.location.href='<?php echo BASE_URL; ?>/voyages?filter=adventure'">Aventure</button>
                    <button class="filter-btn <?php if($filter == 'relaxation') echo 'active'; ?>" onclick="window.location.href='<?php echo BASE_URL; ?>/voyages?filter=relaxation'">Détente</button>
                    <button class="filter-btn <?php if($filter == 'cultural') echo 'active'; ?>" onclick="window.location.href='<?php echo BASE_URL; ?>/voyages?filter=cultural'">Culturel</button>
                    <button class="filter-btn <?php if($filter == 'weekend') echo 'active'; ?>" onclick="window.location.href='<?php echo BASE_URL; ?>/voyages?filter=weekend'">Week-end</button>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="trips-cards">
            <div class="trip-card">
                <img src="assets/img//trips/Edimbourgh.png" alt="Rue d'Edimbourgh">
                <div class="trip-card-content">
                    <div class="trip-card-details">
                        <div class="trip-card-specs">
                            <p class="category">Culturel</p>
                            <p class="country">Royaume-Uni</p>
                            <p class="duration"><span>durée</span> 3 jours</p>
                        </div>
                        <h2>Week-end à Edimbourg</h2>
                        <p>Le temps d’une respiration, découvrez cette magnifique ville avec son coeur de ville médiéval, ses coins de nature à quelques mètres du centre-ville et ses pubs légendaires.</p>
                    </div>
                    <div class="trip-card-comment">
                        <p><span class="material-icons">format_quote</span>
                        Edimbourg est une ville animée et pleine de recoins à découvrir. Grâce à son réseau de tram et à ses bus, tout peut se faire à pieds. Prendre un verre devant un match de rugby dans un pub de la vieille ville après avoir gravi Calton’s Hill et s’être assis sur le trône du Roi Arthur au sommet est une expérience incroyable!
                        <span class="material-icons">format_quote</span></p>
                    </div>
                </div>
            </div>
            
            <div class="trip-card">
                <img src="assets/img/trips/Japon.png" alt="Temple au Japon">
                <div class="trip-card-content">
                    <div class="trip-card-details">
                        <div class="trip-card-specs">
                            <p class="category">Culturel</p>
                            <p class="country">Japon</p>
                            <p class="duration"><span>durée</span> 28 jours</p>
                        </div>  
                        <h2>Le Japon entre modernité et tradition</h2>
                        <p>Offrez-vous une aventure au Japon: que vous aimiez la ville ou les randonnées en nature, ce pays vous permet de concilier toutes vos envies. Du Kumano Kodo à la déambulation au coeur d’OSaka, de l’île de Myajima au Mémorial d’Hiroshima, chaque journée est une découverte d’une culture riche, ancestrale et diverse. C’est le genre de voyage qui vous transforme et vous apaise.</p>
                    </div>
                    <div class="trip-card-comment">
                        <p><span class="material-icons">format_quote</span>
                        La nature japonaise est aussi époustouflante que les villes sont animées. Le Kumano kodo est une expérience incroyable qui vous reconnecte avec la nature et vous fait découvrir des arbres de 800 ans, des daims en quantité et une nature apaisante. Les villes comme Osaka et Tokyo sont des microcosmes pleines de vie, qui ne semblent jamais dormir. Je n’ai qu’une hâte: y retourner.
                        <span class="material-icons">format_quote</span></p>
                    </div>
                </div>
            </div>
            
            <div class="trip-card">
                <img src="assets/img/trips/Montreal.png" alt="Un balcon à Montréal">
                <div class="trip-card-content">
                    <div class="trip-card-details">
                        <div class="trip-card-specs">
                            <p class="category">Aventure</p>
                            <p class="country">Canada</p>
                            <p class="duration"><span>durée</span> 15 jours</p>
                        </div>
                        <h2>S’aventurer au Canada</h2>
                        <p>En hiver, en été, le Canada est un pays à découvrir en toute saison! En été, on alterne entre la découverte des baleines et des bélugas dans le Saint Laurent et les petits cafés le long des voies piétonnes à Montréal ou Québec. En hiver, ce sont les glissades et les expéditions en raquette qui se terminent au coin du feu dans un chalet au bord d’un lac gelé ou les matchs de Hockey dans des bars survoltés qui sont à découvrir. Au Canada, quelle que soit la saison, il y en a pour tout le monde.</p>
                    </div>
                    <div class="trip-card-comment">
                        <p><span class="material-icons">format_quote</span>
                        J’ai été surprise par la chaleur et la beauté des parcs en été au Québec. Après avoir connu Montréal sous la neige, je ne pensais pas qu’il pouvait y avoir un tel contraste! C’est vraiment agréable peu importe la saison!
                        <span class="material-icons">format_quote</span></p>
                    </div>
                </div>
            </div>
            
            <div class="trip-card">
                <img src="assets/img/trips/Loire.png" alt="un château de la Loire">
                <div class="trip-card-content">
                    <div class="trip-card-details">
                        <div class="trip-card-specs">
                            <p class="category">Détente</p>
                            <p class="country">France</p>
                            <p class="duration"><span>durée</span> 3 jours</p>
                        </div>
                        <h2>Une fin de semaine en Touraine</h2>
                        <p>Des châteaux de la Loire en vélo à la découverte des caves, il y en a des choses à faire dans ce coin de France. N’hésitez pas à nous demander un circuit qui allie les découvertes touristiques, gastronomiques et les balades dans la nature!</p>
                    </div>
                    <div class="trip-card-comment">
                        <p><span class="material-icons">format_quote</span>
                            Trois jours exceptionnels qui ont été une vraie respiration dans mon quotidien de fou. Le tour des châteaux en vélo est vraiment une façon agréable de découvrir les châteaux et la nature de cette région. En plus, on se sent moins coupable de profiter de la gastronomie locale ensuite et de déguster de délicieux vins.
                        <span class="material-icons">format_quote</span></p>
                    </div>
                </div>
            </div>

            <div class="trip-card">
                <img src="assets/img/trips/Tarn.png" alt="Les gorges de l'Aveyron">
                <div class="trip-card-content">
                    <div class="trip-card-details">
                        <div class="trip-card-specs">
                            <p class="category">Aventure</p>
                            <p class="country">France</p>
                            <p class="duration"><span>durée</span> 8 jours</p>
                        </div>
                        <h2>Les gorges de l'Aveyron</h2>
                        <p>Des paysages sublimes dans le Tarn à découvrir en voiture ou en van. Accessible en famille, avec vos animaux, ce circuit est un bon moyen de décompresser et de profiter de la nature à peu de frais!</p>
                    </div>
                    <div class="trip-card-comment">
                        <p><span class="material-icons">format_quote</span>
                        J’ai fait ce séjour avec mes deux chiens et mon conjoint: c’était vraiment agréable d’avoir des points de chute et des activités dog friendly! En plus, les paysages étaient à couper le souffle!
                        <span class="material-icons">format_quote</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>