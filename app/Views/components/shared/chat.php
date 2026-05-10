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
            <div class="message-footer">
                <span class="timestamp"><?= htmlspecialchars($message->getCreatedAt()) ?></span>
                <?php
                if ($isOwnMessage) {?>
                    <p class="status"><?= $message->isRead() ? '<span class="material-symbols-outlined read" data-icon="visibility">visibility</span>' : '<span class="material-symbols-outlined unread" data-icon="visibility">visibility</span>' ?></p>
                <?php } ?>
            </div>
        </div>
        <?php
    }
    ?>
    </div>
    
    <form class="chat-form" method="POST" action="<?= BASE_URL ?>/messages/send">
        <input type="hidden" name="trip_id" value="<?= $trip->getId() ?>">
        <input type="hidden" name="redirect_url" value="<?= $_SERVER['REQUEST_URI'] ?>">
        
        <textarea name="message" placeholder="Message..." required></textarea>
        
        <button class="send-button" type="submit"><span class="material-symbols-outlined" data-icon="send">send</span></button>
    </form>
</div>