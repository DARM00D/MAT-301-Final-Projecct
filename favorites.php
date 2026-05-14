<?php
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

requireRole(['buyer', 'renter']);

$db = new RealEstateDatabase();
$message = '';
$userId = (int)$_SESSION['user']['userId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $propertyId = (int)($_POST['propertyId'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($propertyId > 0 && $action === 'remove') {
        $db->removeFavorite($userId, $propertyId);
        $message = 'Property removed from favorites.';
    }
}

$favorites = $db->getFavoritesByUser($userId);
?>
<?php include 'includes/header.php'; ?>
<h2>My Favorites</h2>

<?php if ($message): ?>
    <p class="success"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<?php if (empty($favorites)): ?>
    <p>You have no saved properties. <a href="properties.php">Browse listings</a> to add some.</p>
<?php else: ?>
    <?php foreach ($favorites as $fav): ?>
        <div class="card">
            <h3><?= htmlspecialchars($fav['propertyTitle']) ?></h3>
            <p><strong>City:</strong> <?= htmlspecialchars($fav['city']) ?></p>
            <p><strong>Price:</strong> $<?= number_format($fav['price'], 2) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($fav['status']) ?></p>
            <p><strong>Agent:</strong> <?= htmlspecialchars($fav['agentName']) ?></p>
            <p><strong>Saved On:</strong> <?= htmlspecialchars($fav['savedDate']) ?></p>
            <a href="property_details.php?id=<?= (int)$fav['propertyId'] ?>">View Details</a>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="propertyId" value="<?= (int)$fav['propertyId'] ?>">
                <input type="hidden" name="action" value="remove">
                <button type="submit">Remove</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
