<?php

add_action('init', 'espy_register_export_yoast_endpoint');

function espy_register_export_yoast_endpoint() {
    add_rewrite_rule('^esporta-yoast/?$', 'index.php?export_yoast=1', 'top');
}

add_filter('query_vars', 'espy_add_export_yoast_query_var');
function espy_add_export_yoast_query_var($vars) {
    $vars[] = 'export_yoast';
    return $vars;
}

add_action('template_redirect', 'espy_handle_export_yoast');
function espy_handle_export_yoast() {
    if (get_query_var('export_yoast')) {
        espy_check_admin();

        global $wpdb;

        $results = $wpdb->get_results("
            SELECT post_id,
                   MAX(CASE WHEN meta_key = '_yoast_wpseo_title' THEN meta_value END) AS meta_title,
                   MAX(CASE WHEN meta_key = '_yoast_wpseo_metadesc' THEN meta_value END) AS meta_description
            FROM {$wpdb->prefix}postmeta
            WHERE meta_key IN ('_yoast_wpseo_title', '_yoast_wpseo_metadesc')
            GROUP BY post_id
        ");

        if (empty($results)) {
            wp_die('Errore nella creazione del CSV, tabelle Yoast non trovate, il plugin è installato?');
        }

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment;filename="meta_yoast_esportato.csv"');

        $output = fopen('php://output', 'w');
        if ($output === false) {
            wp_die('Errore nella creazione del file CSV, tabelle vuote o corrotte. Prova a riparare le tabelle del db.');
        }

        fwrite($output, "\xEF\xBB\xBF");
        fputcsv($output, ['post_url', 'meta_title', 'meta_description']);

        foreach ($results as $row) {
            $post_url = get_permalink($row->post_id);
            fputcsv($output, [$post_url, $row->meta_title, $row->meta_description]);
        }

        fclose($output);
        exit;
    }
}
