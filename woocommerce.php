<?php
/**
 * Template base para páginas de WooCommerce
 */

get_header();
?>

<div class="container my-5">
    <div class="row">
        <?php woocommerce_content(); ?>
    </div>
</div>

<?php
get_footer();