<?php function input_with_border(
    string $type,
    string $id = '',
    string $name = '',
    string $placeholder = '',
    string $value = ''
) {
    ?>

    <div class='flex p-3 bg-blue-500 rounded-4xl focus-within:bg-red-500'>
        <input id='<?= $id ?>' class='w-2xl text-white px-2 focus:outline-none' 
        type='<?= $type ?>' name='<?= $name ?>' placeholder='<?= $placeholder ?>'
        value="<?= $value ?>"/>
    </div>

<?php
}
?>
