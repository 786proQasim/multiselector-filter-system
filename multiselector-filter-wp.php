<?php
/**
 * Plugin Name: Multiselector Filter System
 * Description: A comprehensive multiselector filter system for WordPress, similar to ProPakistani packages
 * Version: 1.0.0
 * Author: Your Name
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class MultiselectorFilterSystem {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_shortcode('multiselector_filter', array($this, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    public function init() {
        // Register any custom post types or taxonomies if needed
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('multiselector-filter', plugins_url('assets/css/multiselector-filter.css', __FILE__));
        wp_enqueue_script('multiselector-filter', plugins_url('assets/js/multiselector-filter.js', __FILE__), array('jquery'), '1.0.0', true);
        
        wp_localize_script('multiselector-filter', 'multiselector_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('multiselector_nonce')
        ));
    }
    
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'type' => 'packages',
            'category' => 'all'
        ), $atts);
        
        ob_start();
        ?>
        <div class="multiselector-filter-wrapper">
            <div class="max-w-7xl mx-auto px-4 py-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Plan Finder</h1>
                
                <!-- Tab Navigation -->
                <div class="bg-white rounded-lg shadow-sm mb-6">
                    <div class="flex border-b">
                        <button class="tab-btn flex-1 py-4 px-6 text-center font-medium text-gray-700 hover:text-teal-600 border-b-2 border-transparent active" data-tab="voice">
                            Voice
                        </button>
                        <button class="tab-btn flex-1 py-4 px-6 text-center font-medium text-gray-700 hover:text-teal-600 border-b-2 border-transparent" data-tab="sms">
                            SMS
                        </button>
                        <button class="tab-btn flex-1 py-4 px-6 text-center font-medium text-gray-700 hover:text-teal-600 border-b-2 border-transparent" data-tab="data">
                            Data
                        </button>
                        <button class="tab-btn flex-1 py-4 px-6 text-center font-medium text-gray-700 hover:text-teal-600 border-b-2 border-transparent" data-tab="mifi">
                            MiFi / Dongles
                        </button>
                        <button class="tab-btn flex-1 py-4 px-6 text-center font-medium text-gray-700 hover:text-teal-600 border-b-2 border-transparent" data-tab="internet">
                            Internet
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Filters Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
                            
                            <!-- Network Filter -->
                            <div class="filter-section">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Network</h4>
                                <div class="flex flex-wrap gap-2">
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="network" data-value="jazz">Jazz</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="network" data-value="telenor">Telenor</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="network" data-value="ufone">Ufone</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="network" data-value="zong">Zong</button>
                                </div>
                            </div>

                            <!-- On/Off Net Toggle -->
                            <div class="filter-section">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Call Type</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">On Net</span>
                                        <div class="toggle-switch" data-filter="onnet"></div>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Off Net</span>
                                        <div class="toggle-switch" data-filter="offnet"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Validity Filter -->
                            <div class="filter-section">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Validity</h4>
                                <div class="flex flex-wrap gap-2">
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="validity" data-value="daily">Daily</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="validity" data-value="weekly">Weekly</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="validity" data-value="monthly">Monthly</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="validity" data-value="2hours">2 Hours</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="validity" data-value="2days">2 Days</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="validity" data-value="3days">3 Days</button>
                                </div>
                            </div>

                            <!-- Coverage Area -->
                            <div class="filter-section">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Coverage Area</h4>
                                <div class="flex flex-wrap gap-2">
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="area" data-value="lahore">Lahore</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="area" data-value="karachi">Karachi</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="area" data-value="islamabad">Islamabad</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="area" data-value="rawalpindi">Rawalpindi</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="area" data-value="multan">Multan</button>
                                    <button class="filter-tag px-3 py-1 text-sm border border-gray-300 rounded-full" data-filter="area" data-value="faisalabad">Faisalabad</button>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="filter-section">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Price Range</h4>
                                <div class="space-y-2">
                                    <input type="range" class="range-slider w-full" id="priceRange" min="0" max="1000" value="1000">
                                    <div class="flex justify-between text-xs text-gray-500">
                                        <span>Rs. 0</span>
                                        <span id="priceValue">Rs. 1000</span>
                                        <span>Rs. 1000</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Range -->
                            <div class="filter-section">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Data (GB)</h4>
                                <div class="space-y-2">
                                    <input type="range" class="range-slider w-full" id="dataRange" min="0" max="100" value="100">
                                    <div class="flex justify-between text-xs text-gray-500">
                                        <span>0 GB</span>
                                        <span id="dataValue">100 GB</span>
                                        <span>100 GB</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Unlimited Checkbox -->
                            <div class="filter-section">
                                <label class="flex items-center">
                                    <input type="checkbox" id="unlimitedCheck" class="mr-2 text-teal-600">
                                    <span class="text-sm text-gray-700">Unlimited</span>
                                </label>
                            </div>

                            <!-- Clear Filters -->
                            <button id="clearFilters" class="w-full py-2 px-4 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                Clear All Filters
                            </button>
                        </div>
                    </div>

                    <!-- Results Area -->
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Results</h3>
                                <div class="text-sm text-gray-600">
                                    <span id="resultsCount">0</span> packages found
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Network</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Package</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">On-Net</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Off-Net</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">SMS</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Data</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Validity</th>
                                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resultsTable">
                                        <!-- Results will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
}

// Initialize the plugin
new MultiselectorFilterSystem();

// AJAX handler for dynamic filtering
add_action('wp_ajax_filter_packages', 'handle_package_filtering');
add_action('wp_ajax_nopriv_filter_packages', 'handle_package_filtering');

function handle_package_filtering() {
    check_ajax_referer('multiselector_nonce', 'nonce');
    
    $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
    $tab = isset($_POST['tab']) ? sanitize_text_field($_POST['tab']) : 'voice';
    
    // Here you would query your actual data source
    // For now, returning sample data
    $results = array(
        array(
            'network' => 'Jazz',
            'name' => 'Jazz Super Bundle',
            'onNet' => '1000',
            'offNet' => '100',
            'sms' => '1000',
            'data' => '2GB',
            'validity' => 'Monthly',
            'price' => 500
        ),
        array(
            'network' => 'Telenor',
            'name' => 'Telenor Easy Card',
            'onNet' => '500',
            'offNet' => '50',
            'sms' => '500',
            'data' => '1GB',
            'validity' => 'Monthly',
            'price' => 350
        )
    );
    
    wp_send_json_success($results);
}
?>
