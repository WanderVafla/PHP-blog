<?php
/**
 * @param array{
 *      id?: string,
 *      name?: string,
 *      type?: string,
 *      placeholder?: string,
 *      value?: string
 * } $params
 * @param string|null $label
 * @param string|null $error
 */
function input_with_border(
    array $params,
    ?string $label = null,
    ?string $error = null
) {
    $defaultParams = [
        'id' => null,
        'type' => 'text',
        'name' => null,
        'placeholder' => null,
        'value' => null,
    ];

    $id = $params['id'] ?? null;
    $type = $params['type'] ?? null;
    $name = $params['name'] ?? null;
    $placeholder = $params['placeholder'] ?? null;
    $value = $params['value'] ?? null;
    ?>
    <div class="flex flex-col">
        
        <?php if ($label): ?>
            <label for="<?= htmlspecialchars($id) ?>">
                <?= htmlspecialchars($label) ?>
            </label>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <span class="error">
                <?= htmlspecialchars($error) ?>
            </span>
        <?php endif; ?>
        
        <div class='flex p-3 bg-blue-500 rounded-4xl focus-within:bg-red-500'>
            <input
                class='w-2xl text-white px-2 focus:outline-none'
                <?= $id ? 'id="' . htmlspecialchars($id) . '"' : null ?>
                <?= $type ? 'type="' . htmlspecialchars($type) . '"' : null ?>
                <?= $name ? 'name="' . htmlspecialchars($name) . '"' : null ?>
                <?= $placeholder ? 'placeholder="' . htmlspecialchars($placeholder) . '"' : null ?>
                <?= $value ? 'value="' . htmlspecialchars($value) . '"' : null ?>
            >
        </div>
    </div>

<?php
}
?>
