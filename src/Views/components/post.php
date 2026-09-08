<?= require_once "categorieName.php"  ?>
<?php
function post(int $id, string $title, string $content, string $path, ?string $categorie = null)
{
    if (!file_exists($path)) {
        $path = "/asset/notImage.png";
    } ?>
    <div class='flex flex-col gap-2'>
        <img src="<?= $path ?>" alt="Post image" class="size-60 object-cover rounded-4xl" >
        <div class='flex flex-col px-2 py-1 gap-1'>
            <p class='text-xl font-semibold'><a class="hover:text-red-500" href="/post?id=<?= $id ?>"><?= $title ?></a></p>
            <?php categorieName($categorie ?? ""); ?>
            <p class='text-wrap truncate w-60 h-30' >
                <?= $content ?>
            </p>
        </div>
    </div>
<?php
}
?>
