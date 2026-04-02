<?php
include_once 'includes/header.php';
?>
    <main>    
        <section class="home">
            <div class="home-text">
                <h1>L'évasion en toute <span>sérénité</span>.</h1>
                <p>Votre voyage sur mesure, organisé par des passionnés, réservé par vous-même.</p>
                <button class="btn-primary" onclick="window.location.href='index.php?action=register'">Demander mon voyage</button>
            </div>
            <div class="home-images">
                <img class="photo-front" src="assets/img/home_front.png" alt="Image de voyage d'un hôtel de luxe">
                <img class="photo-back" src="assets/img/home_back.png" alt="Image de voyage au Japon">
            </div>
        </section>
        <section class="method">
            <p class="section-hit">NOTRE MÉTHODE</p>
            <h2>Un parcours fluide vers l'exceptionnel</h2>
            <div class="method-steps-cards">
                <div class="method-step-card">
                    <img src="assets/img/met_brief.png" alt="Image de la première étape de notre méthode : la demande de voyage">
                    <div class="header-step-card">
                    <h3>Le Brief</h3>
                    <p class="step-number">1</p>
                    </div>
                    <p class="method-step-card-text">Remplissez notre formulaire en ligne pour partager vos envies, vos rêves et vos contraintes. Nous écoutons chaque détail pour cerner votre vision avec précision.</p>
                </div>
                <div class="method-step-card">
                    <img src="assets/img/met_creation.png" alt="Image de la deuxième étape de notre méthode : la création du voyage sur mesure">
                    <div class="header-step-card">
                        <h3>La Création</h3>
                        <p class="step-number">2</p>
                    </div>
                    <p class="method-step-card-text">Votre expert dessine un itinéraire exclusif, sélectionnant pépites locales, adresses secrètes et logistique optimale. Si vous l'acceptez, nous vous donnons accès à votre espace personnel.</p>
                </div>
                <div class="method-step-card">
                    <img src="assets/img/met_adjust.png" alt="Image de la troisième étape de notre méthode : la réservation du voyage par le client">
                    <div class="header-step-card">
                        <h3>L'ajustement</h3>
                        <p class="step-number">3</p>
                    </div>
                    <p class="method-step-card-text">Nous affinons ensemble chaque étape jusqu'à ce que votre carnet de voyage soit absolument parfait et équilibré. Vous pouvez faire le suivi de vos réservation dans votre espace personnel et communiquer avec votre expert à tout moment via notre messagerie.</p>
                </div>
                <div class="method-step-card">
                    <img src="assets/img/met_follow.png" alt="Image de la quatrième étape de notre méthode : le suivi du voyage">
                    <div class="header-step-card">
                        <h3>Le Départ</h3>
                        <p class="step-number">4</p>
                    </div>
                    <p class="method-step-card-text">Nous restons en contact tout au long de votre voyage pour nous assurer que tout se passe bien.</p>
                </div>
            </div>
        </section>
        <section class="destinations">
            <p class="section-hit">INSPIRATIONS</p>
            <h2>Nos Destinations de prédilection</h2>
            <div class="destination-cards">
                <div class="destination-card">
                    <img src="assets/img/dest_France.png" alt="Image de la destination de France">
                    <h3>France</h3>
                    <p>Découvrez les paysages magnifiques, la culture riche et la gastronomie exceptionnelle de la France.</p>
                </div>
                <div class="destination-card">
                    <img src="assets/img/dest_Canada.png" alt="Image de la destination de Canada">
                    <h3>Canada</h3>
                    <p>Explorez les paysages naturels époustouflants, les villes dynamiques et la culture diversifiée du Canada.</p>
                </div>
                <div class="destination-card">
                    <img src="assets/img/dest_Japon.png" alt="Image de la destination de Japon">
                    <h3>Japon</h3>
                    <p>Le raffinement du silence, le ballet des cerisiers et l'harmonie parfaite entre tradition séculaire et modernité.</p>
                </div>
            </div>
        </section>
        <section class="call-to-action">
            <img src="assets/img/cta_ScenicView.png" alt="Image de montagnes">
            <h2>Prêt pour votre prochaine aventure mémorable ?</h2>
            <p>Nos experts sont prêts à transformer vos désirs en réalité.</p>
            <button class="btn-primary" onclick="window.location.href='index.php?action=register'">DÉMARRER L'EXPÉRIENCE</button>
        </section>
    </main>
<?php
include_once 'includes/footer.php';
?>