<?php
session_start();
require 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove'])) {
    $product_id = (int)$_POST['remove'];
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
        $_SESSION['cart'] = $cart;
    }
}
?>

<h1>Ваш кошик</h1>

<?php if (empty($cart)): ?>
    <p class="empty">Кошик порожній.</p>
<?php else: ?>
    <table class="cart">
        <tr>
            <th>Назва</th>
            <th>Ціна</th>
            <th>Кількість</th>
            <th>Сума</th>
        </tr>
        <?php $total = 0; ?>
        <?php foreach ($cart as $item): ?>
            <?php $sum = $item['price'] * $item['quantity']; ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
                <td class="quantity"><?= $item['quantity'] ?></td>
                <td><?= number_format($sum, 2) ?></td>
                <td class="remove-cell">
                    <form method="POST">
                        <input type="hidden" name="remove" value="<?= $item['id'] ?>">
                        <button type="submit" class="remove">Remove</button>
                    </form>
                </td>
            </tr>
            <?php $total += $sum; ?>
        <?php endforeach; ?>
        <tr class="total">
            <td colspan="4"><strong>Разом:</strong></td>
            <td><strong><?= number_format($total, 2) ?> $</strong></td>
        </tr>
    </table>
    <div class="buttons-container">
        <a href="index.php">Скасувати</a>
        <a href="">Оплатити</a>
    </div>
<?php endif; 
require 'includes/footer.php' ?>
