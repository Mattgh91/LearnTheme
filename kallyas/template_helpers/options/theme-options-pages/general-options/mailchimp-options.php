<?php
/**
 * Theme options > General Options  > Mailchimp options
 */

$admin_options[] = array (
    'slug'        => 'mailchimp_options',
    'parent'      => 'general_options',
    "name"        => __( 'MAILCHIMP OPTIONS', 'zn_framework' ),
    "description" => __( 'The options below are related to Mailchimp (Online email marketing) platform integration in Kallyas. ', 'zn_framework' ),
    "id"          => "info_title12",
    "type"        => "zn_title",
    "class"       => "zn_full zn-custom-title-large zn-toptabs-margin"
);

$admin_options[] = array (
	'slug'        => 'mailchimp_options',
    'parent'      => 'general_options',
    "name"        => __( "Mailchimp API KEY", 'zn_framework' ),
    "description" => __( "Paste your mailchimp api key that will be used by the mailchimp widget.", 'zn_framework' ),
    "id"          => "mailchimp_api",
    "std"         => '',
    "type"        => "text"
);

$admin_options[] = array (
    'slug'        => 'mailchimp_options',
    'parent'      => 'general_options',
    "name"        => __( '<span class="dashicons dashicons-editor-help"></span> HELP:', 'zn_framework' ),
    "description" => __( 'Below you can find quick access to documentation, video documentation or our support forum.', 'zn_framework' ),
    "id"          => "mco_title",
    "type"        => "zn_title",
    "class"       => "zn_full zn-custom-title-md zn-top-separator zn-sep-dark"
);

$admin_options[] = zn_options_video_link_option( 'http://support.hogash.com/kallyas-videos/#4zt7-E985Xw', __( 'Click here to access video tutorial for this options section.', 'zn_framework' ), array(
    'slug'        => 'mailchimp_options',
    'parent'      => 'general_options'
));
$admin_options[] = zn_options_doc_link_option( 'http://support.hogash.com/documentation/configure-mailchimp/', array(
    'slug'        => 'mailchimp_options',
    'parent'      => 'general_options'
));

$admin_options[] = wp_parse_args( znpb_general_help_option( 'zn-admin-helplink' ), array(
    'slug'        => 'mailchimp_options',
    'parent'      => 'general_options',
));