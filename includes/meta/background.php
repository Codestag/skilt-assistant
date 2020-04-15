<?php
/**
 * @package Stag_Customizer
 */

add_action( 'add_meta_boxes', 'stag_metabox_post_background' );

function stag_metabox_post_background() {
	$post_id = get_the_ID();

	$meta_box = array(
		'id'          => 'stag-metabox-background',
		'title'       =>  __( 'Background Settings', 'skilt' ),
		'description' => __( 'Here you can customize post background settings.', 'skilt' ),
		'page'        => apply_filters( 'stag_post_background_post_types', array( 'post', 'page' ) ),
		'context'     => 'normal',
		'priority'    => 'high',
		'fields'      => array(
			array(
				'name' => __( 'Background Image', 'skilt' ),
				'desc' => __( 'Choose background image for this post.', 'skilt' ),
				'id'   => 'post-background-image',
				'type' => 'file',
				'std'  => ''
			),
			array(
				'name' => 'Post Likes',
				'desc' => null,
				'id'   => '_post-likes',
				'type' => 'hidden',
				'std'  => stag_get_post_meta( 'settings', $post_id, '_post-likes' )
			),
			array(
				'name' => __( 'Background Color', 'skilt' ),
				'desc' => __( 'Choose background color for this post.', 'skilt' ),
				'id'   => 'post-background-color',
				'type' => 'color',
				'std'  => null
			),
			array(
                'name' => __( 'Cover Opacity', 'skilt' ),
                'desc' => __( 'Choose the opacity for the post&lsquo;s background.', 'skilt' ),
                'id'   => 'post-background-opacity',
                'type' => 'number',
                'std'  => '40',
                'step'  => '5',
                'min'  => '0'
            ),
            array(
                'name'    => __( 'Cover Filter', 'skilt' ),
                'desc'    => __( 'Applies CSS3 filter on cover image.', 'skilt' ),
                'id'      => 'post-background-filter',
                'type'    => 'select',
                'std'     => 'none',
                'options' => array(
                    'none'       => __( 'None', 'skilt' ),
                    'grayscale'  => __( 'Grayscale', 'skilt' ),
                    'sepia'      => __( 'Sepia', 'skilt' ),
                    'blur'       => __( 'Blur', 'skilt' ),
                    'hue-rotate' => __( 'Hue Rotate', 'skilt' ),
                    'contrast'   => __( 'Contrast', 'skilt' ),
                    'brightness' => __( 'Brightness', 'skilt' ),
                    'invert'     => __( 'Invert', 'skilt' )
                ),
			),
		)
	);

	stag_add_meta_box( $meta_box );
}
