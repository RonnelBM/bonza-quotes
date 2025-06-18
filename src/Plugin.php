<?php
namespace BonzaQuotes;

use BonzaQuotes\Database\QuoteRepository;
use BonzaQuotes\Frontend\QuoteForm;
use BonzaQuotes\Admin\QuotesAdmin;

class Plugin {
    public static function run() {
        $repo = new QuoteRepository();
        register_activation_hook( BONZA_QUOTES_FILE, [ $repo, 'install' ] );

        $form = new QuoteForm( $repo );
        add_shortcode( 'bonza_quote_form', [ $form, 'render_shortcode' ] );

        if ( is_admin() ) {
            $admin = new QuotesAdmin( $repo );
            add_action( 'admin_menu', [ $admin, 'register_menu' ] );
            add_action( 'admin_post_update_quote_status', [ $admin, 'handle_status_update' ] );
        }

        add_action( 'admin_post_nopriv_submit_bonza_quote', [ $form, 'handle_submit' ] );
        add_action( 'admin_post_submit_bonza_quote', [ $form, 'handle_submit' ] );
    }
}
