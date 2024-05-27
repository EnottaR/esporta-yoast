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
        <div class="mg-setup-notice">
            <h1>Esporta Yoast Meta</h1>
        </div>
        <?php
        if (isset($_POST['action']) && $_POST['action'] === 'espy_export_action') {
            espy_generate_csv_table(); // Se il pulsante "Esporta tabelle" è stato cliccato, mostra la tabella CSV
        } else {
            ?>
            <form method="post" action="admin-post.php">
                <?php wp_nonce_field('espy_export', 'espy_export_nonce'); ?>
                <input type="hidden" name="action" value="espy_export_action">
                <p>
                    <input type="submit" class="button button-primary" value="Esporta tabelle">
                </p>
            </form>
            <?php
        }
        ?>
    </div>
    <?php
}

// Funzione per generare la tabella CSV
function espy_generate_csv_table() {
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
        echo 'Nessun dato trovato.';
        return;
    }
    ?>
    <table class="widefat">
        <thead>
            <tr>
                <th>Post URL</th>
                <th>Meta Title</th>
                <th>Meta Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row) : ?>
                <tr>
                    <td><?php echo get_permalink($row->post_id); ?></td>
                    <td><?php echo $row->meta_title; ?></td>
                    <td><?php echo $row->meta_description; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <form method="post" action="admin-post.php">
        <input type="hidden" name="action" value="espy_export_action">
        <input type="hidden" name="espy_export_nonce" value="<?php echo wp_create_nonce('espy_export'); ?>">
        <input type="hidden" name="espy_export_csv" value="true">
        <p>
            <input type="submit" class="button button-primary" value="Scarica il file CSV">
        </p>
    </form>
    <?php
}
