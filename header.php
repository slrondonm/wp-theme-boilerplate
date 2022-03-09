<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section
 * and everything up until <div id="content">
 *
 * php version 7.2.10
 *
 * @category Functions
 * @package MyThemes
 * @author IDEAMOS TU WEB <webmaster@ideamostuweb.com>
 * @license GNU General Public License v2 or later
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */

global $wp_theme_boilerplate;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>

    <?php wp_head(); ?>
</head>

<body>

    <?php wp_body_open(); ?>
