<?php
function post(string $title, string $content)
{
    ?>
    <div class='flex flex-col gap-2'>
        <div class='w-60 h-60 bg-blue-500 rounded-4xl'></div>
        <div class='flex flex-col px-2 py-1 gap-1'>
            <p class='text-xl font-semibold'><a class="hover:text-red-500" href="single-detail-page.php"><?= $title ?></a></p>
            <p class='text-wrap truncate w-60 h-30' >
                <?= $content ?>
            </p>
        </div>
    </div>
<?php
}
?>
