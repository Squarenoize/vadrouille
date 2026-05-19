// Scroll automatique vers le bas du chat
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.querySelector('.messages');
    
    if (messagesContainer) {
        // Scroll vers le bas au chargement
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        // Optionnel : Scroll vers le bas après l'envoi d'un message
        const chatForm = document.querySelector('.chat-form');
        if (chatForm) {
            chatForm.addEventListener('submit', function() {
                setTimeout(function() {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }, 100);
            });
        }
    }
});
