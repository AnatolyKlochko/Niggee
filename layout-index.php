<?php
// Protection from directly call
if (! defined('FRAMEWORK_NS' ) ) exit;

?><!DOCTYPE html>
<html lang="<?php echo_lang() ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="<?php echo_description() ?>">
        <meta name="author" content="<?php echo_author() ?>">
        <link href="/file/logo.png" rel="shortcut icon">
        <title><?php echo_title() ?></title>
        <!-- Layout Styles -->
        <?php echo_part('styles' ) ?>
        <!-- Identifiers for Custom Layout and Page Styles -->
        <?php echo_identifiers() ?>
        <!-- Custom Layout Styles -->
        <div id="<?= layout_styles_id() ?>"><?php echo_layout_styles() ?></div>
        <!-- Custom Page Styles -->
        <div id="<?= page_styles_id() ?>"><?php echo_page_styles() ?></div>
    </head>
    <body ng-app="healthBlog">
      <!-- LogIn :hover link -->
      <div id="login" class="login" ><a href="/login"></a></div>
      <!-- Navigation -->
      <?php inc_part('menu' ) ?>
      <!-- Page Content -->
      <div id="<?= page_content_id() ?>" ng-view>
        <?php echo_page() ?>
      </div>
      <hr>
      <!-- Footer -->
      <?php inc_part('footer' ) ?>
    </body>
    <!-- Common layout scripts -->
    <?php echo_part('scripts' ) ?>
    <!-- Custom scripts for this layout and page -->
    <div id="<?= layout_script_id() ?>"><?php echo_layout_script() ?></div>
    <div id="<?= page_script_id() ?>"><?php echo_page_script() ?></div>
</html>