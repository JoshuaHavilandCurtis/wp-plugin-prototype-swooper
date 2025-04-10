<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/manifest.json">
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body>
    <div id="map"></div>

    <?php wp_footer(); ?>

</body>
</html>
