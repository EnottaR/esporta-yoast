<?php

function espy_check_admin() {
    if (!current_user_can('administrator')) {
        wp_die('Permesso negato');
    }
}
