<?php
?>
<div class="chat-container">
    <div class="messages">
    <?php
    foreach ($messages as $message) {
        $isOwnMessage = $message->getSenderId() === $user->getId();
        ?>
        <div class="message <?= $isOwnMessage ? 'own-message' : 'received-message' ?>">
            <p class="sender"><?= htmlspecialchars($message->getSenderFirstname()) ?></p>
            <p><?= htmlspecialchars($message->getMessage()) ?></p>
            <span class="timestamp"><?= htmlspecialchars($message->getCreatedAt()) ?></span>
        </div>
        <?php
    }
    ?>
    <form method="POST" action="<?= BASE_URL ?>/messages/send">
        <input type="hidden" name="trip_id" value="<?= $trip->getId() ?>">
        <input type="hidden" name="redirect_url" value="<?= $_SERVER['REQUEST_URI'] ?>">
        
        <textarea name="message" placeholder="Message..." required></textarea>
        
        <button class="send-button" type="submit"><span class="material-symbols-outlined" data-icon="send">send</span></button>
    </form>
</div>