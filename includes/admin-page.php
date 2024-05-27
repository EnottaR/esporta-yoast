<?php

add_action('admin_menu', 'espy_add_admin_menu');
function espy_add_admin_menu() {
    add_menu_page(
        'Esporta Yoast Meta', 
        'Esporta Yoast Meta', 
        'manage_options', 
        'esporta-yoast', 
        'espy_admin_page_content', 
        'dashicons-download', 
        80
    );
}

function espy_admin_page_content() {
    ?>
    <div class="wrap">
        <h1>Esporta Yoast Meta</h1>
        <form method="post" action="">
            <?php wp_nonce_field('espy_export', 'espy_export_nonce'); ?>
            <input type="hidden" name="espy_export_action" value="export_csv">
            <p>
                <input type="submit" class="btn-primario" value="Esporta tabelle">
            </p>
        </form>
    </div>
    <?php
}

add_action('admin_post_espy_export_action', 'espy_handle_export_request');
function espy_handle_export_request() {
    if (isset($_POST['espy_export_action']) && $_POST['espy_export_action'] === 'export_csv') {
        if (!current_user_can('manage_options')) {
            wp_die('Permesso negato');
        }
        if (!check_admin_referer('espy_export', 'espy_export_nonce')) {
            wp_die('Verifica nonce fallita');
        }

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
