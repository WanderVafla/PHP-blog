<?php
require_once "../src/Views/components/input.php";
?>

<?php 
/** 
 * @param array{
 *      id?: string,
 *      name?: string,
 *      type?: string,
 *      placeholder?: string,
 *      value?: string,
 * } $inputs
 * @param bool|null $csrf_token
 * @param string $action
 */
function modal(array $inputs, ?bool $csrf_token = false, string $action, array &$errors) { ?>
<!-- Outer Wrapper: Fills screen, centers the modal box, stays on top -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4">
  <!-- Backdrop: Dark overlay behind the modal -->
  <div class="fixed inset-0 bg-black/50"></div>
  <!-- Modal Box: Content goes here (Relative puts it on top of backdrop) -->
  <form action="" method="POST" class="relative rounded-2xl bg-white p-6 max-w-sm w-full">

      
      <?php foreach ($inputs as $input): ?>
        <?php input_with_border(params: $input, label: $input['placeholder']) ?>
      <?php endforeach; ?>

      <?php if ($csrf_token == true): ?>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">
      <?php endif; ?>

      <?php if ($action): ?>
        <input type="hidden" name="profilAction" value=<?= $action ?> >
      <?php endif; ?>

    <div >
        <button type="submit">
            Save
        </button>
        <button type="button">
            <a href="/profil">Close</a>
        </button>
    </div>

    
  </form>
</div>

<?php
}
?>
