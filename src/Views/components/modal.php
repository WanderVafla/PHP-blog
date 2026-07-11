<?php 
require_once "../src/Views/components/input.php";
?>
<?php function modal(string $inputsType = 'text') { ?>
<!-- Outer Wrapper: Fills screen, centers the modal box, stays on top -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4">
  
  <!-- Backdrop: Dark overlay behind the modal -->
  <div class="fixed inset-0 bg-black/50"></div>

  <!-- Modal Box: Content goes here (Relative puts it on top of backdrop) -->
  <form class="relative rounded-2xl bg-white p-6 max-w-sm w-full">
    
      <?php input_with_border(type: $inputsType, placeholder: 'Old Password') ?>
      <?php input_with_border(type: $inputsType, placeholder: 'New Password') ?>
    
    <button type="submit">
      Save
    </button>

  </form>
</div>

<?php 
}
?>