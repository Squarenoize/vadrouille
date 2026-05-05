<?php
?>
<h1 class="chats-page-title">Messagerie</h1>
<?php
if (empty($chats)) { ?>
    <p class="no-chats-message">Aucune conversation pour le moment.</p>
<?php
} else { ?>
    <div class="chats-list">
        <?php foreach ($chats as $chat) { ?>
            <a href="<?= BASE_URL ?>/admin/trips/<?= $chat['trip_id'] ?>" class="chat-item">
                <?php if ($chat['unread_count'] > 0): ?>
                    <span class="sidebar-badge"><?= $chat['unread_count'] ?></span>
                <?php endif; ?>
                <div class="chat-item-header">
                    <h2 class="chat-item-title"><?= htmlspecialchars($chat['trip_name']) ?></h2>
                    <p class="chat-item-sender"><?= htmlspecialchars($chat['sender_firstname'] . ' ' . $chat['sender_lastname']) ?></p>
                </div>
                <div class="chat-item-body">
                <p class="chat-item-last-message"><?= htmlspecialchars($chat['last_message']) ?></p>
                <p class="chat-item-timestamp"><?= htmlspecialchars($chat['last_message_time']) ?></p>
                </div>
            </a>
        <?php } ?>
    </div>
<?php
}