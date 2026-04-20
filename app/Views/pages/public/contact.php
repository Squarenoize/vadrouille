<?php
$showForm = true;
?>
        <section class="contact">
            <div class="contact-side">
                <p class="section-hit">PREMIER CONTACT</p>
                <h1>Imaginez votre prochaine <span>évasion.</span></h1>
                <p class="side-text">Partagez vos aspirations avec nous. Que ce soit une
                    quête de sérénité ou une soif d'aventure, nos
                    experts prennent en compte vos envies pour vous créer un séjour sur-mesure que vous n'aurez plus qu'à réserver.
                </p>
                <div class="image-container">
                    <img src="assets/img/Boudha2.png" alt="Sculpture d'un boudha pour offrandes aux pèlerins avec un bonnet de laine rose">
                    <p>"Le luxe n'est pas un lieu mais un sentiment"</p>
                </div>
            </div>
            <div class="contact-form">
                <?php if (isset($_SESSION['request_success'])) { ?>
                    <p class="success-message"><?= htmlspecialchars($_SESSION['request_success']) ?></p>
                    <?php unset($_SESSION['request_success']); ?>
                <?php } else { ?>
                    <form action="<?php echo BASE_URL; ?>/contact" method="post">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">Prénom</label>
                                <input type="text" id="first_name" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Nom</label>
                                <input type="text" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Téléphone</label>
                                <input type="tel" id="phone" name="phone">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Type de voyage</label>
                                <div class="radio-button-group">
                                    <input type="radio" id="adventure" name="trip_type" value="adventure" required>
                                    <label for="adventure" class="radio-button">Aventure</label>
                                    
                                    <input type="radio" id="relaxation" name="trip_type" value="relaxation">
                                    <label for="relaxation" class="radio-button">Détente</label>
                                    
                                    <input type="radio" id="cultural" name="trip_type" value="cultural">
                                    <label for="cultural" class="radio-button">Culturel</label>
                                    
                                    <input type="radio" id="weekend" name="trip_type" value="weekend">
                                    <label for="weekend" class="radio-button">Week-end</label>
                                    
                                    <input type="radio" id="other" name="trip_type" value="other">
                                    <label for="other" class="radio-button">Autre</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="destination">Destination souhaitée</label>
                                <select id="destination" name="destination" required>
                                    <option value="france">France</option>
                                    <option value="canada">Canada</option>
                                    <option value="japan">Japon</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="budget">Budget</label>
                                <input type="text" id="budget" name="budget" placeholder="Ex: 2000€" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="start_country">Pays de départ</label>
                                <input type="text" id="start_country" name="start_country" placeholder="Ex: France, Belgique...">
                            </div>
                            <div class="form-group">
                                <label for="desired_start">Date de départ souhaitée</label>
                                <input type="date" id="desired_start" name="desired_start">
                            </div>
                            <div class="form-group">
                                <label for="duration">Durée du voyage (jours)</label>
                                <input type="number" id="duration" name="duration" placeholder="Ex: 10" min="1" max="365">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="travelers_adult_count">Nombre d'adultes</label>
                                <input type="number" id="travelers_adult_count" name="travelers_adult_count" placeholder="Ex: 2" min="0" max="20" value="1" required>
                            </div>
                            <div class="form-group">
                                <label for="travelers_child_count">Nombre d'enfants</label>
                                <input type="number" id="travelers_child_count" name="travelers_child_count" placeholder="Ex: 0" min="0" max="20" value="0" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="message">Votre message</label>
                                <textarea id="message" name="message" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" id="conditions_accepted" name="conditions_accepted" value="1" required>
                            <label for="conditions_accepted">
                                J'accepte que mes données soient utilisées pour être recontacté(e) dans le cadre de ma demande, conformément à la 
                                <a href="<?= BASE_URL ?>/privacy" target="_blank">politique de confidentialité</a>.
                            </label>
                        </div>
                        <p class="form-info">
                            Les informations recueillies sont utilisées uniquement pour traiter votre demande de voyage. 
                            Elles ne seront jamais partagées avec des tiers.
                        </p>
                        <button type="submit" class="btn-primary">Envoyer la demande</button>
                    </form>
                <?php } ?>
            </div>

        </section>