<?php
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'home';
}
include_once 'includes/header.php';
?>
    <main>
        <?php    
        switch ($action) {
            case 'home':
                include_once 'includes/home.php';
                break;
            case 'trips':
                include_once 'includes/trips.php';
                break;
            case 'contact':
                include_once 'includes/contact.php';
                break;
            case 'about':
                include_once 'includes/about.php';
                break;
            case 'terms':
                include_once 'includes/terms.php';
                break;
            default:
                include_once 'includes/error404.php';
        }
        ?>
    </main>
<?php
include_once 'includes/footer.php';