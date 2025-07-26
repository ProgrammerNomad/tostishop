<?php
/**
 * Firebase Configuration
 * TostiShop Theme - Firebase Settings Registration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Firebase settings with WordPress
 */
function tostishop_register_firebase_settings() {
    // Add Firebase options
    add_option('tostishop_firebase_api_key', '');
    add_option('tostishop_firebase_project_id', '');
    add_option('tostishop_firebase_auth_domain', '');
    add_option('tostishop_firebase_sender_id', '');
    add_option('tostishop_firebase_app_id', '');
    add_option('tostishop_firebase_measurement_id', '');

    // Register settings for the admin
    register_setting('general', 'tostishop_firebase_api_key');
    register_setting('general', 'tostishop_firebase_project_id');
    register_setting('general', 'tostishop_firebase_auth_domain');
    register_setting('general', 'tostishop_firebase_sender_id');
    register_setting('general', 'tostishop_firebase_app_id');
    register_setting('general', 'tostishop_firebase_measurement_id');
}
add_action('admin_init', 'tostishop_register_firebase_settings');

/**
 * Get Firebase configuration array
 */
function tostishop_get_firebase_config() {
    return array(
        'apiKey' => get_option('tostishop_firebase_api_key', ''),
        'authDomain' => get_option('tostishop_firebase_auth_domain', ''),
        'projectId' => get_option('tostishop_firebase_project_id', ''),
        'storageBucket' => get_option('tostishop_firebase_project_id', '') . '.appspot.com',
        'messagingSenderId' => get_option('tostishop_firebase_sender_id', ''),
        'appId' => get_option('tostishop_firebase_app_id', ''),
        'measurementId' => get_option('tostishop_firebase_measurement_id', '')
    );
}

/**
 * Check if Firebase is properly configured
 */
function tostishop_is_firebase_configured() {
    $api_key = get_option('tostishop_firebase_api_key', '');
    $project_id = get_option('tostishop_firebase_project_id', '');
    $auth_domain = get_option('tostishop_firebase_auth_domain', '');
    $app_id = get_option('tostishop_firebase_app_id', '');
    
    return !empty($api_key) && !empty($project_id) && !empty($auth_domain) && !empty($app_id);
}

/**
 * Add Firebase settings to General Settings page
 */
function tostishop_add_firebase_settings_fields() {
    add_settings_section(
        'tostishop_firebase_settings',
        'TostiShop Firebase Authentication Settings',
        'tostishop_firebase_settings_section_callback',
        'general'
    );

    add_settings_field(
        'tostishop_firebase_api_key',
        'Firebase API Key',
        'tostishop_firebase_api_key_callback',
        'general',
        'tostishop_firebase_settings'
    );

    add_settings_field(
        'tostishop_firebase_auth_domain',
        'Firebase Auth Domain',
        'tostishop_firebase_auth_domain_callback',
        'general',
        'tostishop_firebase_settings'
    );

    add_settings_field(
        'tostishop_firebase_project_id',
        'Firebase Project ID',
        'tostishop_firebase_project_id_callback',
        'general',
        'tostishop_firebase_settings'
    );

    add_settings_field(
        'tostishop_firebase_sender_id',
        'Firebase Messaging Sender ID',
        'tostishop_firebase_sender_id_callback',
        'general',
        'tostishop_firebase_settings'
    );

    add_settings_field(
        'tostishop_firebase_app_id',
        'Firebase App ID',
        'tostishop_firebase_app_id_callback',
        'general',
        'tostishop_firebase_settings'
    );

    add_settings_field(
        'tostishop_firebase_measurement_id',
        'Firebase Measurement ID',
        'tostishop_firebase_measurement_id_callback',
        'general',
        'tostishop_firebase_settings'
    );
}
add_action('admin_init', 'tostishop_add_firebase_settings_fields');

/**
 * Section callback
 */
function tostishop_firebase_settings_section_callback() {
    echo '<p>Configure your Firebase project settings. You can find these values in your Firebase project console.</p>';
    echo '<div style="background: #f1f1f1; padding: 15px; margin: 10px 0; border-radius: 5px;">';
    echo '<strong>📋 Step-by-Step Instructions:</strong>';
    echo '<ol style="margin: 10px 0 0 20px;">';
    echo '<li>Go to <a href="https://console.firebase.google.com/" target="_blank">Firebase Console</a></li>';
    echo '<li>Select your project (or create a new one)</li>';
    echo '<li>Click on Project Settings (gear icon)</li>';
    echo '<li>Scroll down to "Your apps" section</li>';
    echo '<li>Click on the web app icon (&lt;/&gt;) to add a web app</li>';
    echo '<li>Register your app and copy the config values</li>';
    echo '<li>Enable Authentication → Sign-in methods → Phone and Google</li>';
    echo '</ol>';
    echo '</div>';
}

/**
 * Field callbacks
 */
function tostishop_firebase_api_key_callback() {
    $value = get_option('tostishop_firebase_api_key', '');
    echo '<input type="text" name="tostishop_firebase_api_key" value="' . esc_attr($value) . '" class="regular-text" placeholder="AIzaSyC..." />';
    echo '<p class="description">Your Firebase Web API Key</p>';
}

function tostishop_firebase_auth_domain_callback() {
    $value = get_option('tostishop_firebase_auth_domain', '');
    echo '<input type="text" name="tostishop_firebase_auth_domain" value="' . esc_attr($value) . '" class="regular-text" placeholder="your-project.firebaseapp.com" />';
    echo '<p class="description">Your Firebase Auth Domain</p>';
}

function tostishop_firebase_project_id_callback() {
    $value = get_option('tostishop_firebase_project_id', '');
    echo '<input type="text" name="tostishop_firebase_project_id" value="' . esc_attr($value) . '" class="regular-text" placeholder="your-project-id" />';
    echo '<p class="description">Your Firebase Project ID</p>';
}

function tostishop_firebase_sender_id_callback() {
    $value = get_option('tostishop_firebase_sender_id', '');
    echo '<input type="text" name="tostishop_firebase_sender_id" value="' . esc_attr($value) . '" class="regular-text" placeholder="123456789012" />';
    echo '<p class="description">Your Firebase Messaging Sender ID</p>';
}

function tostishop_firebase_app_id_callback() {
    $value = get_option('tostishop_firebase_app_id', '');
    echo '<input type="text" name="tostishop_firebase_app_id" value="' . esc_attr($value) . '" class="regular-text" placeholder="1:123456789012:web:abc123" />';
    echo '<p class="description">Your Firebase App ID</p>';
}

function tostishop_firebase_measurement_id_callback() {
    $value = get_option('tostishop_firebase_measurement_id', '');
    echo '<input type="text" name="tostishop_firebase_measurement_id" value="' . esc_attr($value) . '" class="regular-text" placeholder="G-XXXXXXXXXX" />';
    echo '<p class="description">Your Firebase Measurement ID (Optional - for Analytics)</p>';
}
