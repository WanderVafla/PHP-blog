<?php function categorieName(string $categorieName)
{ ?>
    <?php if (isset($categorieName)): ?>
        <span class="text-red-600"><?= htmlspecialchars($categorieName) ?></span>
    <?php endif; ?>
<?php } ?>