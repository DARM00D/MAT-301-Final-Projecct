<?php
require_once 'config/config.php';
require_once 'classes/RealEstateDatabase.php';

$db = new RealEstateDatabase();

$propertyId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$property = $db->getPropertyById($propertyId);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $userId = (int)$_SESSION['user']['userId'];
    $action = $_POST['action'] ?? '';

    if ($action === 'add_favorite') {
        if (!$db->isFavorite($userId, $propertyId)) {
            $db->addFavorite($userId, $propertyId);
            $message = 'Property added to favorites!';
        }
    } elseif ($action === 'remove_favorite') {
        $db->removeFavorite($userId, $propertyId);
        $message = 'Property removed from favorites.';
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Property Details</h2>

<?php if ($message): ?>
    <p class="success"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<?php if (!$property): ?>
    <p class="error">Property not found.</p>
<?php else: ?>
    <div class="card">
        <h3><?= htmlspecialchars($property['title']) ?></h3>
        <p><strong>Type:</strong> <?= htmlspecialchars($property['propertyType']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($property['address']) ?></p>
        <p><strong>City:</strong> <?= htmlspecialchars($property['city']) ?></p>
        <p><strong>Price:</strong> $<?= number_format($property['price'], 2) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($property['status']) ?></p>
        <p><strong>Agent:</strong> <?= htmlspecialchars($property['agentName']) ?></p>
    </div>

    <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['userType'], ['buyer', 'renter'], true)): ?>
        <?php $alreadyFavorited = $db->isFavorite((int)$_SESSION['user']['userId'], $propertyId); ?>
        <form method="POST" style="display:inline;">
            <?php if ($alreadyFavorited): ?>
                <input type="hidden" name="action" value="remove_favorite">
                <button type="submit">Remove from Favorites</button>
            <?php else: ?>
                <input type="hidden" name="action" value="add_favorite">
                <button type="submit">Save to Favorites</button>
            <?php endif; ?>
        </form>
        &nbsp;
        <a href="submit_inquiry.php?propertyId=<?= (int)$property['propertyId'] ?>">Submit Inquiry</a>
    <?php endif; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
