jQuery(document).ready(function($) {
    'use strict';

    let activeFilters = {
        network: [],
        onnet: false,
        offnet: false,
        validity: [],
        area: [],
        price: 1000,
        data: 100,
        unlimited: false
    };

    let currentTab = 'voice';
    let isLoading = false;

    // Initialize
    initMultiselectorFilter();

    function initMultiselectorFilter() {
        bindEvents();
        applyFilters();
    }

    function bindEvents() {
        // Tab switching
        $('.tab-btn').on('click', function() {
            $('.tab-btn').removeClass('active border-teal-600 text-teal-600');
            $(this).addClass('active border-teal-600 text-teal-600');
            currentTab = $(this).data('tab');
            applyFilters();
        });

        // Filter tags
        $('.filter-tag').on('click', function() {
            const $this = $(this);
            const filterType = $this.data('filter');
            const filterValue = $this.data('value');
            
            $this.toggleClass('active');
            
            if ($this.hasClass('active')) {
                if (!activeFilters[filterType].includes(filterValue)) {
                    activeFilters[filterType].push(filterValue);
                }
            } else {
                activeFilters[filterType] = activeFilters[filterType].filter(v => v !== filterValue);
            }
            
            applyFilters();
        });

        // Toggle switches
        $('.toggle-switch').on('click', function() {
            const $this = $(this);
            const filterType = $this.data('filter');
            
            $this.toggleClass('active');
            activeFilters[filterType] = $this.hasClass('active');
            applyFilters();
        });

        // Range sliders
        $('#priceRange').on('input', function() {
            activeFilters.price = parseInt($(this).val());
            $('#priceValue').text('Rs. ' + $(this).val());
            applyFilters();
        });

        $('#dataRange').on('input', function() {
            activeFilters.data = parseInt($(this).val());
            $('#dataValue').text($(this).val() + ' GB');
            applyFilters();
        });

        // Unlimited checkbox
        $('#unlimitedCheck').on('change', function() {
            activeFilters.unlimited = $(this).is(':checked');
            applyFilters();
        });

        // Clear filters
        $('#clearFilters').on('click', function() {
            clearAllFilters();
        });
    }

    function applyFilters() {
        if (isLoading) return;
        
        isLoading = true;
        $('.multiselector-filter-wrapper').addClass('loading');
        
        $.ajax({
            url: multiselector_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_packages',
                nonce: multiselector_ajax.nonce,
                filters: activeFilters,
                tab: currentTab
            },
            success: function(response) {
                if (response.success) {
                    displayResults(response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error filtering packages:', error);
            },
            complete: function() {
                isLoading = false;
                $('.multiselector-filter-wrapper').removeClass('loading');
            }
        });
    }

    function displayResults(data) {
        const $tbody = $('#resultsTable');
        const $count = $('#resultsCount');
        
        $tbody.empty();
        $count.text(data.length);

        if (data.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-500">
                        No packages found matching your criteria.
                    </td>
                </tr>
            `);
            return;
        }

        data.forEach(function(item) {
            const row = `
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 text-sm">${escapeHtml(item.network)}</td>
                    <td class="py-3 px-4 text-sm font-medium">${escapeHtml(item.name)}</td>
                    <td class="py-3 px-4 text-sm">${escapeHtml(item.onNet)}</td>
                    <td class="py-3 px-4 text-sm">${escapeHtml(item.offNet)}</td>
                    <td class="py-3 px-4 text-sm">${escapeHtml(item.sms)}</td>
                    <td class="py-3 px-4 text-sm">${escapeHtml(item.data)}</td>
                    <td class="py-3 px-4 text-sm">${escapeHtml(item.validity)}</td>
                    <td class="py-3 px-4 text-sm font-semibold">Rs. ${escapeHtml(item.price)}</td>
                </tr>
            `;
            $tbody.append(row);
        });

        // Add animation
        $tbody.addClass('results-updated');
        setTimeout(() => $tbody.removeClass('results-updated'), 300);
    }

    function clearAllFilters() {
        activeFilters = {
            network: [],
            onnet: false,
            offnet: false,
            validity: [],
            area: [],
            price: 1000,
            data: 100,
            unlimited: false
        };
        
        $('.filter-tag').removeClass('active');
        $('.toggle-switch').removeClass('active');
        $('#priceRange').val(1000);
        $('#dataRange').val(100);
        $('#unlimitedCheck').prop('checked', false);
        $('#priceValue').text('Rs. 1000');
        $('#dataValue').text('100 GB');
        
        applyFilters();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Sample data for demonstration (fallback when AJAX fails)
    const sampleData = {
        voice: [
            { network: 'Jazz', name: 'Jazz Super Bundle', onNet: '1000', offNet: '100', sms: '1000', data: '2GB', validity: 'Monthly', price: 500 },
            { network: 'Telenor', name: 'Telenor Easy Card', onNet: '500', offNet: '50', sms: '500', data: '1GB', validity: 'Monthly', price: 350 },
            { network: 'Zong', name: 'Zong Super Card', onNet: '2000', offNet: '200', sms: '2000', data: '4GB', validity: 'Monthly', price: 650 },
            { network: 'Ufone', name: 'Ufone Super Card', onNet: '1500', offNet: '150', sms: '1500', data: '3GB', validity: 'Monthly', price: 550 }
        ],
        sms: [
            { network: 'Jazz', name: 'Jazz SMS Bundle', onNet: '0', offNet: '0', sms: '10000', data: '0GB', validity: 'Monthly', price: 100 },
            { network: 'Telenor', name: 'Telenor SMS Package', onNet: '0', offNet: '0', sms: '5000', data: '0GB', validity: 'Weekly', price: 50 }
        ],
        data: [
            { network: 'Jazz', name: 'Jazz 4G Package', onNet: '0', offNet: '0', sms: '0', data: '10GB', validity: 'Monthly', price: 300 },
            { network: 'Zong', name: 'Zong 4G Package', onNet: '0', offNet: '0', sms: '0', data: '20GB', validity: 'Monthly', price: 500 }
        ],
        mifi: [
            { network: 'Jazz', name: 'Jazz 4G Device', onNet: '0', offNet: '0', sms: '0', data: '50GB', validity: 'Monthly', price: 2000 },
            { network: 'Zong', name: 'Zong 4G Device', onNet: '0', offNet: '0', sms: '0', data: '100GB', validity: 'Monthly', price: 3000 }
        ],
        internet: [
            { network: 'PTCL', name: 'PTCL Broadband', onNet: '0', offNet: '0', sms: '0', data: 'Unlimited', validity: 'Monthly', price: 2500 },
            { network: 'StormFiber', name: 'StormFiber', onNet: '0', offNet: '0', sms: '0', data: 'Unlimited', validity: 'Monthly', price: 3000 }
        ]
    };

    // Fallback for AJAX
    if (typeof multiselector_ajax === 'undefined') {
        console.warn('AJAX not available, using sample data');
        
        // Override applyFilters to use sample data
        window.applyFilters = function() {
            const data = sampleData[currentTab] || [];
            let filteredData = data;

            // Apply network filter
            if (activeFilters.network.length > 0) {
                filteredData = filteredData.filter(item => 
                    activeFilters.network.some(network => 
                        item.network.toLowerCase().includes(network.toLowerCase())
                    )
                );
            }

            // Apply price filter
            filteredData = filteredData.filter(item => item.price <= activeFilters.price);

            // Apply unlimited filter
            if (activeFilters.unlimited) {
                filteredData = filteredData.filter(item => 
                    item.data.toLowerCase().includes('unlimited')
                );
            }

            displayResults(filteredData);
        };
    }
});
