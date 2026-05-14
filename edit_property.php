<?php
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

requireRole(['agent']);

$db = new RealEstateDatabase();
$message = '';
$agentId = (int)$_SESSION['user']['userId'];
$propertyId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$property = $db->getPropertyById($propertyId);

if (!$property) {
    die('<p class="error">Property not found.</p>');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title        = trim($_POST['title'] ?? '');
    $propertyType = trim($_POST['propertyType'] ?? '');
    $address      = trim($_POST['address'] ?? '');
    $city         = trim($_POST['city'] ?? '');
    $price        = (float)($_POST['price'] ?? 0);
    $status       = $_POST['status'] ?? 'available';

    if ($title && $propertyType && $address && $city && $price > 0) {
        try {
            $sql = "UPDATE Properties SET title = :title, propertyType = :propertyType,
                    address = :address, city = :city, price = :price, status = :status
                    WHERE propertyId = :propertyId AND agentId = :agentId";
            $stmt = (new Database())->connect()->prepare($sql);
            $stmt->execute([
                ':title'        => $title,
                ':propertyType' => $propertyType,
                ':address'      => $address,
                ':city'         => $city,
                ':price'        => $price,
                ':status'       => $status,
                ':propertyId'   => $propertyId,
                ':agentId'      => $agentId
            ]);
            $message = 'Property updated successfully.';
            $property = $db->getPropertyById($propertyId);
        } catch (Throwable $e) {
            $message = 'Error: ' . $e->getMessage();
        }
    } else {
        $message = 'Please complete all required fields.';
    }
}
?>
<?php include 'includes/header.php'; ?>
<h2>Edit Property</h2>

<?php if ($message): ?>
    <p class="success"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Title</label>
    <input type="text" name="title" value="<?= htmlspecialchars($property['title']) ?>" required>

    <label>Property Type</label>
    <input type="text" name="propertyType" value="<?= htmlspecialchars($property['propertyType']) ?>" required>

    <label>Address</label>
    <input type="text" name="address" value="<?= htmlspecialchars($property['address']) ?>" required>

    <label>City</label>
    <input type="text" name="city" value="<?= htmlspecialchars($property['city']) ?>" required>

    <label>Price</label>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($property['price']) ?>" required>

    <label>Status</label>
    <select name="status">
        <option value="available" <?= $property['status'] === 'available' ? 'selected' : '' ?>>Available</option>
        <option value="sold"      <?= $property['status'] === 'sold'      ? 'selected' : '' ?>>Sold</option>
        <option value="rented"    <?= $property['status'] === 'rented'    ? 'selected' : '' ?>>Rented</option>
    </select>

    <button type="submit">Save Changes</button>
    <a href="dashboard.php">Cancel</a>
</form>

<?php include 'includes/footer.php'; ?>
