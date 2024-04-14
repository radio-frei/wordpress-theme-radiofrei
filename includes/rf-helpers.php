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
function rf_strip_time($date_time)
{
    return mysql2date('d. F Y', $date_time);
}
function rf_strip_date($date_time)
{
    return mysql2date('H:i', $date_time);
}

function rf_get_weekday($date_time)
{
    return mysql2date('D, d.m.y', $date_time);
}

function rf_val_date($date)
{
    if (!empty($date)) {
        if (date_create_from_format('Y-m-d', $date) !== false) {
            return $date;
        }
    }
};

function rf_limit_words_12($string)
{
    return wp_trim_words($string, 12);
}
