<?php

function espy_flush_rewrite_rules_on_activation() {
    espy_register_export_yoast_endpoint();
    flush_rewrite_rules();
}

function espy_flush_rewrite_rules_on_deactivation() {
    flush_rewrite_rules();
}
