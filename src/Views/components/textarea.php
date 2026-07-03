<?php
function textarea(
    string $name = "",
    string $placeholder = "",
    string $value = "",
) {
    ?>

    <div class="flex p-3 bg-blue-500 rounded-4xl focus-within:bg-red-500">
        <textarea name="<?= $name ?>" class="w-2xl h-2xl text-white px-2 focus:outline-none resize-none field-sizing-content" placeholder="<?= $placeholder ?>"><?= $value ?></textarea>
    </div>

<?php
} ?>
