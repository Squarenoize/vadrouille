<?php
$showForm = false;
?>
        <section class="contact">
            <div class="contact-side">
                <p class="section-hit">PREMIER CONTACT</p>
                <h1>Dessinez votre prochaine <span>évasion.</span></h1>
                <p class="side-text">Partagez vos aspirations avec nous. Que ce soit une
                    quête de sérénité ou une soif d'aventure, nos
                    concierges façonnent chaque détail pour une
                    expérience sur mesure.
                </p>
                <div class="image-container">
                    <img src="assets/img/rockStele.png" alt="Sculpture d'une stèle de roches empilées">
                    <p>"Le luxe n'est pas un lieu mais un sentiment"</p>
                </div>
            </div>
            <div class="contact-form">
                <?php if ($showForm) { ?>
                <form action="index.php?action=contact" method="post">
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
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Type de voyage</label>
                            <div class="radio-button-group">
                                <input type="radio" id="adventure" name="travel_type" value="adventure" required>
                                <label for="adventure" class="radio-button">Aventure</label>
                                
                                <input type="radio" id="relaxation" name="travel_type" value="relaxation">
                                <label for="relaxation" class="radio-button">Détente</label>
                                
                                <input type="radio" id="cultural" name="travel_type" value="cultural">
                                <label for="cultural" class="radio-button">Culturel</label>
                                
                                <input type="radio" id="weekend" name="travel_type" value="weekend">
                                <label for="weekend" class="radio-button">Week-end</label>
                                
                                <input type="radio" id="other" name="travel_type" value="other">
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
                            <label for="travel_companions">Pays de départ</label>
                            <input type="text" id="travel_companions" name="travel_companions" placeholder="Ex: France, Espagne..." required>
                        </div>
                        <div class="form-group">
                            <label for="travel_dates">Date de départ</label>
                            <input type="date" id="travel_dates" name="travel_dates" required>
                        </div>
                        <div class="form-group">
                            <label for="travel_duration">Durée du voyage</label>
                            <input type="text" id="travel_duration" name="travel_duration" placeholder="Ex: 10 jours" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="adults">Nombre d'adultes</label>
                            <input type="number" id="adults" name="adults" placeholder="Ex: 2" required>
                        </div>
                        <div class="form-group">
                            <label for="children">Nombre d'enfants</label>
                            <input type="number" id="children" name="children" placeholder="Ex: 0" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="message">Votre message</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" id="consent" name="consent" required>
                        <label for="consent">
                            J'accepte que mes données soient utilisées pour être recontacté(e) dans le cadre de ma demande, conformément à la 
                            <a href="privacy.php" target="_blank">politique de confidentialité</a>.
                        </label>
                    </div>
                    <p class="form-info">
                        Les informations recueillies sont utilisées uniquement pour traiter votre demande de voyage. 
                        Elles ne seront jamais partagées avec des tiers.
                    </p>
                    <button type="submit" class="btn-primary">Envoyer la demande</button>
                </form>
                <?php } else { ?>
                    <p class="form-submission-message">Merci pour votre intérêt !<br> 
                    <span class="material-icons">square_foot</span>Site en construction. <br>
                    Pour l'instant, contactez-nous directement <a href="mailto:contact@vadbou.fr">par email</a>.</p>
                <?php } ?>
            </div>

        </section>