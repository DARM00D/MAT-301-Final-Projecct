<?php
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

requireLogin();

$db = new RealEstateDatabase();
$user = $_SESSION['user'];
$userId = (int)$user['userId'];
$userDetails = $db->getUserDetails($userId);
?>
<?php include 'includes/header.php'; ?>
<h2>Dashboard</h2>

<div class="card">
    <p><strong>Welcome:</strong> <?= htmlspecialchars($user['userName']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($user['userType']) ?></p>
    <p><strong>Contact:</strong> <?= htmlspecialchars($user['contactInfo']) ?></p>
</div>

<?php if ($user['userType'] === 'agent'): ?>

    <div class="card">
        <h3>Agent Actions</h3>
        <a href="add_property.php">Add New Property</a>
    </div>

    <div class="card">
        <h3>My Listings</h3>
        <?php
        $allProperties = $db->getAllProperties();
        $myProperties = array_filter($allProperties, fn($p) => $p['agentName'] === $user['userName']);
        ?>
        <?php if (empty($myProperties)): ?>
            <p>You have no listings yet.</p>
        <?php else: ?>
            <?php foreach ($myProperties as $property): ?>
                <div class="card">
                    <strong><?= htmlspecialchars($property['title']) ?></strong>
                    <p><?= htmlspecialchars($property['city']) ?> &mdash; $<?= number_format($property['price'], 2) ?></p>
                    <p>Status: <?= htmlspecialchars($property['status']) ?></p>
                    <a href="property_details.php?id=<?= (int)$property['propertyId'] ?>">View</a>
                    &nbsp;
                    <a href="edit_property.php?id=<?= (int)$property['propertyId'] ?>">Edit</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

<?php elseif ($user['userType'] === 'buyer' || $user['userType'] === 'renter'): ?>

    <div class="card">
        <h3>Quick Actions</h3>
        <a href="properties.php">Browse Properties</a>
        &nbsp;&nbsp;
        <a href="favorites.php">My Favorites</a>
    </div>

    <div class="card">
        <h3>My Favorites</h3>
        <?php $favorites = $db->getFavoritesByUser($userId); ?>
        <?php if (empty($favorites)): ?>
            <p>No saved properties yet. <a href="properties.php">Browse listings</a>.</p>
        <?php else: ?>
            <?php foreach ($favorites as $fav): ?>
                <div class="card">
                    <strong><?= htmlspecialchars($fav['propertyTitle']) ?></strong>
                    <p><?= htmlspecialchars($fav['city']) ?> &mdash; $<?= number_format($fav['price'], 2) ?></p>
                    <p>Status: <?= htmlspecialchars($fav['status']) ?></p>
                    <a href="property_details.php?id=<?= (int)$fav['propertyId'] ?>">View</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>My Inquiries</h3>
        <?php $inquiries = $userDetails['inquiries'] ?? []; ?>
        <?php if (empty($inquiries)): ?>
            <p>You have not submitted any inquiries yet.</p>
        <?php else: ?>
            <?php foreach ($inquiries as $inquiry): ?>
                <div class="card">
                    <strong><?= htmlspecialchars($inquiry['propertyTitle']) ?></strong>
                    <p><?= htmlspecialchars($inquiry['message']) ?></p>
                    <p><small>Sent: <?= htmlspecialchars($inquiry['inquiryDate']) ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>My Transactions</h3>
        <?php $transactions = $userDetails['transactions'] ?? []; ?>
        <?php if (empty($transactions)): ?>
            <p>No transactions on record.</p>
        <?php else: ?>
            <?php foreach ($transactions as $transaction): ?>
                <div class="card">
                    <strong><?= htmlspecialchars($transaction['propertyTitle']) ?></strong>
                    <p>Type: <?= htmlspecialchars($transaction['transactionType']) ?></p>
                    <p>Amount: $<?= number_format($transaction['amount'], 2) ?></p>
                    <p><small>Date: <?= htmlspecialchars($transaction['transactionDate']) ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

<?php endif; ?>

<div class="card">
    <h3>Browse</h3>
    <a href="properties.php">View All Properties</a>
</div>

<?php include 'includes/footer.php'; ?>
