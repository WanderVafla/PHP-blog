<?php
function post(string $title, string $content, string $path)
{
    if (!file_exists($path)) {
        $path = "/asset/notImage.png";
    } ?>
    <div class='flex flex-col gap-2'>
        <img src="<?= $path ?>" alt="Post image" class="size-60 rounded-4xl" >
        <div class='flex flex-col px-2 py-1 gap-1'>
            <p class='text-xl font-semibold'><a class="hover:text-red-500" href="/post"><?= $title ?></a></p>
            <p class='text-wrap truncate w-60 h-30' >
                <?= $content ?>
            </p>
        </div>
    </div>
<?php
}
?>
