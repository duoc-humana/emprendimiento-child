<?php
// Archivo base para cargar el tema.
get_header();
?>


<main>
<!--PRODUCTO DESTACADO +++++++++++++++++++-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 img-fondo">
                    <div class="row d-flex justify-content-center align-items-star">
                        <div class="col-md-12 mt-3">
                            <img src="assets/img/maceta-1.png" alt="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 d-flex justify-content-center">
                            <div class="d-flex flex-column text-center">
                                <span class="txt-esp1">100%</span>
                                <span class="txt-esp2">RECICLADO</span>
                            </div>

                        </div>
                        <div class="col-md-1">

                        </div>
                        <div class="col-md-5 d-flex justify-content-center">
                            <div class="d-flex flex-column text-center">
                                <span class="txt-esp1">83%</span>
                                <span class="txt-esp2">SOSTENIBLE</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 ps-5">
                    <div class="row esp4 mt-5">
                        <div class="col-md-12">
                            <h2>Maceta con material reciclado</h2>
                        </div>
                    </div>
                    <div class="row esp4">
                        <div class="col-md-12">
                            <span class="txt-esp3">$990</span>
                        </div>
                    </div>
                    <div class="row esp5">
                        <div>
                            <p>Esta maceta esta hecha con material reciclado, elaborado con materiales<br> a base de
                                jeans.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <a href="#" class="btn2">Comprar</a>
                        </div>
                        <div class="col-md-6">
                            <a href="#" class="link">Ver productos</a>
                        </div>
                    </div>
                </div>
            </div>


<!-- Sección de banner de empresas -->
<div class="swiper mySwiper">
    <div class="swiper-wrapper">

        <?php
        $query = new WP_Query(array(
            'post_type' => 'empresas_home',
            'posts_per_page' => -1
        ));

        while ($query->have_posts()) :
            $query->the_post();
            $fields = get_post_meta(get_the_ID(), 'empresa', true);
            $img_url = wp_get_attachment_url($fields);
        ?>
        
        <div class="swiper-slide">
            <img class="ajuste" src="<?php echo esc_url($img_url); ?>">
        </div>

        <?php endwhile; wp_reset_postdata(); ?>

    </div>
</div>

</main>        





<?php
get_footer();
?>
