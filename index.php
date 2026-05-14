<?php include 'includes/header.php'; ?>

<h2>Welcome to Vice City Realty</h2>
<p>Your premier destination for luxury properties across South Florida. Whether you're looking to buy, rent, or list — we operate where the money moves.</p>

<div class="card">
    <h3>&#9733; Featured Market</h3>
    <p>Vice City's real estate market is booming. Browse our exclusive listings across the most sought-after locations in South Florida — from oceanfront condos to gated estate homes.</p>
</div>

<div class="card">
    <h3>How It Works</h3>
    <ul>
        <li><strong>Agents</strong> — List and manage premium properties across Vice City</li>
        <li><strong>Buyers</strong> — Browse listings, save favorites, and close deals</li>
        <li><strong>Renters</strong> — Find your next residence and submit inquiries instantly</li>
    </ul>
</div>

<div class="card">
    <h3>Get Started</h3>
    <?php if (!isset($_SESSION['user'])): ?>
        <p><a href="register.php">Create an account</a> to access all features, or <a href="login.php">login</a> if you already have one.</p>
        <p><a href="properties.php">Browse listings</a> without an account.</p>
    <?php else: ?>
        <p>Welcome back, <strong><?= htmlspecialchars($_SESSION['user']['userName']) ?></strong>. Head to your <a href="dashboard.php">Dashboard</a> or <a href="properties.php">browse listings</a>.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
