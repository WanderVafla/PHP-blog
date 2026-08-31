<select name='selected-categories' id='categories-select'>
    <?php if (isset($categories)): ?>
        <option value="null" selected> -- No Categorie -- </option>
        <?php foreach ($categories as $categorie): ?>
            <option value="<?= $categorie['title'] ?>"><?= $categorie['title'] ?></option>
    <?php endforeach;endif; ?>
</select>