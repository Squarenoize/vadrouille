<?php
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
} else {
    $filter = 'all';
}
?>
    <section class="trips">
        <div class="trips-header">
            <h1>Nos Voyages <span>réalisés</span>.</h1>
            <p>Découvrez nos voyages soigneusement élaborés pour vous inspirer d'expériences inoubliables vécuesà travers le monde. Que vous soyez à la recherche d'aventure, de détente, de culture ou de week-end, nous construirons le voyage parfait pour vous.</p>
            <div class="trips-filters">
                <span class="filters-label">Filtrer par :</span>
                <div class="filters-buttons">
                    <button class="filter-btn <?php if($filter == 'all') echo 'active'; ?>" onclick="window.location.href='index.php?action=trips&filter=all'">Tous</button>
                    <button class="filter-btn <?php if($filter == 'adventure') echo 'active'; ?>" onclick="window.location.href='index.php?action=trips&filter=adventure'">Aventure</button>
                    <button class="filter-btn <?php if($filter == 'relaxation') echo 'active'; ?>" onclick="window.location.href='index.php?action=trips&filter=relaxation'">Détente</button>
                    <button class="filter-btn <?php if($filter == 'cultural') echo 'active'; ?>" onclick="window.location.href='index.php?action=trips&filter=cultural'">Culturel</button>
                    <button class="filter-btn <?php if($filter == 'weekend') echo 'active'; ?>" onclick="window.location.href='index.php?action=trips&filter=weekend'">Week-end</button>
                </div>
            </div>
        </div>
        <div class="trips-cards">
            <div class="trip-card">
                <img src="assets/img//trips/Edimbourgh.jpg" alt="Voyage Aventure">
                <div class="trip-card-content">
                    <div class="trip-card-details">
                        <div class="trip-card-specs">
                            <p class="category">Culturel</p>
                            <p class="country">Royaume-Uni</p>
                            <p class="duration">7 jours</p>
                        </div>
                        <h2>Week-end à Edimbourg</h2>
                        <p>Plongez au cœur de la jungle amazonienne pour une aventure inoubliable. Explorez la biodiversité unique, rencontrez les communautés locales et vivez des moments d'adrénaline au milieu de la nature sauvage.</p>
                    </div>
                    <div class="trip-card-comment">
                        <p><span class="material-icons">format_quote</span>
                        Un voyage d'aventure incroyable qui m'a permis de me connecter avec la nature d'une manière que je n'aurais jamais imaginée. Les paysages étaient à couper le souffle et les rencontres avec les communautés locales étaient enrichissantes.
                        <span class="material-icons">format_quote</span></p>
                    </div>
                </div>
            </div>
            
            <div class="trip-card">
                <img src="assets/img/trips/Japon.jpg" alt="Temple au Japon">
                <div class="trip-card-content">
                    <div class="trip-card-details">
                        <div class="trip-card-specs">
                            <p class="category">Culturel</p>
                            <p class="country">Japon</p>
                            <p class="duration">28 jours</p>
                        </div>  
                        <h2>Découvertes au soleil levant</h2>
                        <p>Offrez-vous une escapade de rêve au Japon, le pays du soleil levant. Profitez de paysages magnifiques, de temples historiques et d'une culture riche pour une expérience de détente totale.</p>
                    </div>
                    <div class="trip-card-comment">
                        <p><span class="material-icons">format_quote</span>
                        Un voyage culturel fascinant qui m'a permis de découvrir la richesse du Japon. Des temples majestueux aux traditions uniques, chaque jour était une nouvelle aventure culturelle.
                        <span class="material-icons">format_quote</span></p>
                    </div>
                </div>
            </div>
            
            <div class="trip-card">
                <img src="assets/img/trips/Canada.jpg" alt="Cabane au Canada">
                <div class="trip-card-content">
                    <div class="trip-card-details">
                        <div class="trip-card-specs">
                            <p class="category">Aventure</p>
                            <p class="country">Canada</p>
                            <p class="duration">15 jours</p>
                        </div>
                        <h2>L'hiver en forêt</h2>
                        <p>Découvrez la beauté sauvage du Canada en hiver. Explorez les forêts enneigées, observez la faune locale et profitez d'activités hivernales pour une expérience inoubliable au cœur de la nature.</p>
                    </div>
                    <div class="trip-card-comment">
                        <p><span class="material-icons">format_quote</span>
                        Un voyage hivernal incroyable qui m'a permis de me reconnecter avec la nature. Les paysages enneigés et les activités en plein air étaient tout simplement magiques.
                        <span class="material-icons">format_quote</span></p>
                    </div>
                </div>
            </div>
            
            <div class="trip-card">
                <img src="assets/img/trips/Perpignan.jpg" alt="Plage de Perpignan">
                <div class="trip-card-content">
                    <div class="trip-card-details">
                        <div class="trip-card-specs">
                            <p class="category">Détente</p>
                            <p class="country">France</p>
                            <p class="duration">4 jours</p>
                        </div>
                        <h2>Week-end à Perpignan</h2>
                        <p>Profitez d'un week-end ensoleillé à Perpignan, une ville riche en culture et en histoire. Découvrez ses ruelles pittoresques, ses marchés animés et savourez la cuisine locale pour une escapade mémorable.</p>
                    </div>
                    <div class="trip-card-comment">
                        <p><span class="material-icons">format_quote</span>
                            Un week-end de détente parfait à Perpignan. La ville est charmante, la cuisine délicieuse et l'ambiance relaxante. C'était exactement ce dont j'avais besoin pour me ressourcer.
                        <span class="material-icons">format_quote</span></p>
                    </div>
                </div>
            </div>

            <div class="trip-card">
                <img src="assets/img/trips/Tarn.jpg" alt="Les gorges de l'Aveyron">
                <div class="trip-card-content">
                    <div class="trip-card-details">
                        <div class="trip-card-specs">
                            <p class="category">Aventure</p>
                            <p class="country">France</p>
                            <p class="duration">8 jours</p>
                        </div>
                        <h2>Les gorges de l'Aveyron</h2>
                        <p>Partez pour un week-end magique dans les gorges de l'Aveyron. Flânez dans les ruelles pittoresques, admirez les paysages naturels et profitez d'une ambiance paisible dans cette région incontournable.</p>
                    </div>
                    <div class="trip-card-comment">
                        <p><span class="material-icons">format_quote</span>
                        Un week-end d'aventure inoubliable dans les gorges de l'Aveyron. Les paysages sont à couper le souffle et l'expérience en pleine nature était incroyable.
                        <span class="material-icons">format_quote</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>