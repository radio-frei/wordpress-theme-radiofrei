<?php

/**
 * Radio F.R.E.I.
 * Hilfsfunktionen
 **/


/* Disable WordPress Admin Bar on frontend */
add_filter('show_admin_bar', '__return_false');

/**
 * Allows shortcodes to evaluate special magic tags which appear like magic tags,
 * but the values are mapped through pods_v(). Use this with caution.
 */
define('PODS_SHORTCODE_ALLOW_EVALUATE_TAGS', true);

/**
 * debug helper
 */
function rf_log($data)
{
    if (true === WP_DEBUG) {
        if (is_array($data) || is_object($data)) {
            error_log(print_r($data, true));
        } else {
            if (!empty($data)) {
                error_log($data);
            }
        }
    }
}

/**
 * zur verwendung in pods view und template magic tags, z. B. {@start,rf_strip_date} Uhr
 */
function rf_strip_time($dateTime)
{
    return mysql2date('d. F Y', $dateTime);
}
function rf_strip_date($dateTime)
{
    return mysql2date('H:i', $dateTime);
}

function rf_get_weekday($dateTime)
{
    return mysql2date('D, d.m.y', $dateTime);
}

function rf_val_date($value)
{
    if (!empty($value)) {
        if (date_create_from_format('Y-m-d', $value) !== false) {
            return $value;
        }
    }
};
