<?php
namespace BonzaQuotes\Frontend;

use BonzaQuotes\Database\QuoteRepository;

class QuoteForm {
    private $repo;

    public function __construct( QuoteRepository $repo ) {
        $this->repo = $repo;
    }


    public function render_shortcode( $atts ) {
        $output = '';
    
        if ( isset($_GET['bonza_quote_status']) && $_GET['bonza_quote_status'] === 'success' ) {
            $output .= '<p style="color: green; font-weight: bold;">Thank you! Your quote has been submitted.</p>';
        }
    
        $output .= $this->get_form_html();
    
        return $output;
    }
    
    
    private function get_form_html() {
        ob_start(); ?>
        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <input type="hidden" name="action" value="submit_bonza_quote">
            <?php wp_nonce_field( 'bonza_quote_form', 'bonza_quote_nonce' ); ?>
            <p>
                <label for="bonza_name">Name</label><br>
                <input type="text" id="bonza_name" name="name" required>
            </p>
            <p>
                <label for="bonza_email">Email</label><br>
                <input type="email" id="bonza_email" name="email" required>
            </p>
            <p>
                <label for="bonza_service_type">Service Type</label><br>
                <input type="text" id="bonza_service_type" name="service_type" required>
            </p>
            <p>
                <label for="bonza_notes">Notes</label><br>
                <textarea id="bonza_notes" name="notes" required></textarea>
            </p>
            <p>
                <input type="submit" name="bonza_quote_submit" value="Submit Quote">
            </p>
        </form>
        <?php return ob_get_clean();
    }

    public function handle_submit() {
        if ( ! isset( $_POST['bonza_quote_nonce'] ) || ! wp_verify_nonce( $_POST['bonza_quote_nonce'], 'bonza_quote_form' ) ) {
            return '<p>Security check failed.</p>';
        }
    
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'service_type' => $_POST['service_type'] ?? '',
            'notes' => $_POST['notes'] ?? '',
        ];
    
        // Allow hook in quote submission
        $quote_id = $this->repo->insert( $data );
        do_action( 'bonza_quote_submitted', $quote_id, $data );
    
        // Send email to admin
        $subject = apply_filters( 'bonza_quote_email_subject', 'New Quote Submitted', $data );
        $message = apply_filters(
            'bonza_quote_email_message',
            sprintf(
                "A new quote was submitted by %s (%s).\n\nService: %s\n\nNotes:\n%s\n",
                $data['name'],
                $data['email'],
                $data['service_type'],
                $data['notes']
            ),
            $data
        );

        wp_mail( get_option( 'admin_email' ), $subject, $message );

    
        $redirect = wp_get_referer() ?: home_url();
        wp_redirect( add_query_arg( 'bonza_quote_status', 'success', $redirect ) );
        exit;
    }    
}