<div class="flex gap-3 justify-between p-3 rounded-2xl font-bold absolute bg-green-200 top-0 left-2/5">
    <?= $_SESSION["last_action"] ?? "Rien" ?>
    <a href="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">x</a>
</div>
