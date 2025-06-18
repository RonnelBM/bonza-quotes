<?php
namespace BonzaQuotes\Admin;

use BonzaQuotes\Database\QuoteRepository;

class QuotesAdmin {
    private $repo;

    public function __construct( QuoteRepository $repo ) {
        $this->repo = $repo;
    }

    public function register_menu() {
        add_menu_page(
            'Bonza Quotes',
            'Bonza Quotes',
            'manage_options',
            'bonza-quotes',
            [ $this, 'render_admin_page' ],
            'dashicons-format-status',
            6
        );
    }

    public function render_admin_page() {
        $quotes = $this->repo->get_all(); ?>
        <div class="wrap">
            <h1>Bonza Quotes</h1>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Service Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ( $quotes as $q ): ?>
                    <tr>
                        <td><?php echo esc_html( $q->name ); ?></td>
                        <td><?php echo esc_html( $q->email ); ?></td>
                        <td><?php echo esc_html( $q->service_type ); ?></td>
                        <td><?php echo esc_html( ucfirst( $q->status ) ); ?></td>
                        <td>
                            <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" style="display:inline;">
                                <?php wp_nonce_field( 'update_quote_status_' . $q->id ); ?>
                                <input type="hidden" name="action" value="update_quote_status">
                                <input type="hidden" name="quote_id" value="<?php echo intval( $q->id ); ?>">
                                <select name="status">
                                    <option value="pending"<?php selected( $q->status, 'pending' ); ?>>Pending</option>
                                    <option value="approved"<?php selected( $q->status, 'approved' ); ?>>Approved</option>
                                    <option value="rejected"<?php selected( $q->status, 'rejected' ); ?>>Rejected</option>
                                </select>
                                <input type="submit" class="button" value="Update">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php }

    public function handle_status_update() {
        if ( ! isset( $_POST['quote_id'] ) || ! isset( $_POST['status'] ) ) {
            wp_die( 'Invalid request' );
        }

        $id     = intval( $_POST['quote_id'] );
        $status = sanitize_text_field( $_POST['status'] );
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'update_quote_status_' . $id ) ) {
            wp_die( 'Security check failed' );
        }

        $this->repo->update_status( $id, $status );
        wp_redirect( admin_url( 'admin.php?page=bonza-quotes' ) );
        exit;
    }
}