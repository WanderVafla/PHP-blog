<select name='selected-categories' id='categories-select'>
    <?php if (isset($categories)): ?>
        <option value="null" <?= !$category_id ? 'selected' : '' ?>> -- No Categorie -- </option>
        <?php foreach ($categories as $categorie): ?>
            <option value="<?= $categorie['id'] ?>"<?= $category_id === $categorie['id'] ? 'selected' : '' ?>><?= $categorie['title'] ?></option>
    <?php endforeach;endif; ?>
</select>