<?php

/**
 * Plugin Name: SPT Lever integration
 * Plugin URI:
 * Description: Integrates Lever job offers into wordpress posts.
 * Version: 1.0
 * Author: SEALS
 * Author URI: http://www.schibsted.pl/seals
 */
class SptLeverIntegration
{
    private $jobListingType = 'job_listing';
    private $lever;

    public function __construct()
    {
        include_once(plugin_dir_path(__FILE__) . '/includes/lever-api.php');
        include_once(plugin_dir_path(__FILE__) . '/includes/SptJobOffer.php');
        include_once(plugin_dir_path(__FILE__) . '/includes/Apply.php');

        function lever_enqueue_style() {
            wp_enqueue_style( 'core', plugin_dir_url( __FILE__ ) . 'includes/style.css', false );
        }

        function lever_enqueue_script() {
            wp_enqueue_script( 'my-js', plugin_dir_url( __FILE__ ) . 'includes/script.js', false );
        }

        add_action( 'wp_enqueue_scripts', 'lever_enqueue_style' );
        add_action( 'wp_enqueue_scripts', 'lever_enqueue_script' );


        $this->lever = new LeverAPI();
        add_action('wp_login', array($this, 'lever_update'));

        register_activation_hook(__FILE__, 'stp_lever_plugin_activation');

        function stp_lever_plugin_activation()
        {
            if (!wp_next_scheduled('hourly_lever_update')) {
                wp_schedule_event(time(), 'hourly', 'hourly_lever_update');
            }
        }

        register_deactivation_hook(__FILE__, 'stp_lever_plugin_deactivation');

        function stp_lever_plugin_deactivation() {
            wp_clear_scheduled_hook('hourly_lever_update');
        }

        add_action('hourly_lever_update', array($this, 'lever_update'));

    }



    public function lever_update()
    {
        if (post_type_exists($this->jobListingType)) {
            $offers = json_decode($this->lever->getOffers());
            $this->delete_previous_jobs();
            foreach ($offers->data as $offer) {
                $jobOffer = new SptJobOffer($offer);
                $jobOffer->setOfferData();
            }
        }
    }

    private function delete_previous_jobs(){
        $jobs = get_posts( array( 'post_type' => $this->jobListingType, 'numberposts' => 500));
        foreach( $jobs as $job ) {
            wp_delete_post( $job->ID, true);
        }
    }
}

$SptLeverIntegration = new SptLeverIntegration();
