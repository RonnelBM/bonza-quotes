<?php
namespace BonzaQuotes\Database;

class QuoteRepository {
    private $table;

    public function __construct($table_name = null) {
        global $wpdb;
        $this->table = $table_name ?? $wpdb->prefix . 'bonza_quotes';
    }

    public function install() {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            service_type VARCHAR(255) NOT NULL,
            notes TEXT NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        dbDelta( $sql );
    }

    public function insert(array $data) {
        global $wpdb;
        $wpdb->insert(
            $this->table,
            [
                'name' => sanitize_text_field( $data['name'] ),
                'email' => sanitize_email( $data['email'] ),
                'service_type' => sanitize_text_field( $data['service_type'] ),
                'notes' => sanitize_textarea_field( $data['notes'] ),
                'status' => 'pending',
            ]
        );
        return $wpdb->insert_id;
    }

    public function get_all() {
        global $wpdb;
        return $wpdb->get_results( "SELECT * FROM {$this->table} ORDER BY created_at DESC" );
    }

    public function update_status($id, $status) {
        global $wpdb;
        $wpdb->update(
            $this->table,
            [ 'status' => sanitize_text_field( $status ) ],
            [ 'id' => intval( $id ) ]
        );
    }
}
