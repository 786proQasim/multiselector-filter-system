# Multiselector Filter System

A comprehensive multiselector filter system for WordPress, inspired by the ProPakistani packages website. This system provides advanced filtering capabilities with a modern, responsive design.

## Features

- **Tab-based navigation** (Voice, SMS, Data, MiFi/Dongles, Internet)
- **Multi-select tags** for networks, validity periods, and coverage areas
- **Toggle switches** for binary options (On Net/Off Net)
- **Range sliders** for price and data filtering
- **Real-time filtering** with AJAX support
- **Responsive design** for mobile devices
- **WordPress integration** via shortcode
- **Clean, modern UI** with teal/turquoise color scheme

## Installation

### WordPress Plugin

1. **Upload the plugin:**
   - Upload the entire plugin folder to `/wp-content/plugins/`
   - Or create a new folder `/wp-content/plugins/multiselector-filter/` and upload all files

2. **Activate the plugin:**
   - Go to WordPress Admin → Plugins
   - Find "Multiselector Filter System" and click "Activate"

3. **Use the shortcode:**
   - Add `[multiselector_filter]` to any page or post
   - Optional parameters: `[multiselector_filter type="packages" category="all"]`

### Standalone HTML

1. **Use the HTML file:**
   - Simply open `multiselector-filter.html` in your browser
   - No server setup required

## Usage

### Adding Custom Data

To use your own data with the WordPress plugin, modify the `handle_package_filtering()` function in `multiselector-filter-wp.php`:

```php
function handle_package_filtering() {
    check_ajax_referer('multiselector_nonce', 'nonce');
    
    $filters = isset($_POST['filters']) ? $_POST['filters'] : array();
    $tab = isset($_POST['tab']) ? sanitize_text_field($_POST['tab']) : 'voice';
    
    // Query your custom post type or database
    $args = array(
        'post_type' => 'packages',
        'posts_per_page' => -1,
        'meta_query' => array(
            // Add your meta queries based on filters
        )
    );
    
    $query = new WP_Query($args);
    $results = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $results[] = array(
                'network' => get_post_meta(get_the_ID(), 'network', true),
                'name' => get_the_title(),
                'onNet' => get_post_meta(get_the_ID(), 'on_net_minutes', true),
                'offNet' => get_post_meta(get_the_ID(), 'off_net_minutes', true),
                'sms' => get_post_meta(get_the_ID(), 'sms_count', true),
                'data' => get_post_meta(get_the_ID(), 'data_amount', true),
                'validity' => get_post_meta(get_the_ID(), 'validity', true),
                'price' => get_post_meta(get_the_ID(), 'price', true)
            );
        }
    }
    
    wp_send_json_success($results);
}
```

### Customizing Styles

The system uses Tailwind CSS for styling. You can customize colors and spacing by modifying the CSS variables in `assets/css/multiselector-filter.css`:

```css
:root {
    --primary-color: #14b8a6;
    --secondary-color: #0f766e;
    --border-color: #e5e7eb;
    --text-color: #374151;
}
```

### Adding New Filter Types

To add new filter types, extend the `activeFilters` object in the JavaScript:

```javascript
let activeFilters = {
    network: [],
    onnet: false,
    offnet: false,
    validity: [],
    area: [],
    price: 1000,
    data: 100,
    unlimited: false,
    your_new_filter: [] // Add new filter here
};
```

Then add the corresponding HTML in the filter sidebar and update the filtering logic.

## File Structure

```
multiselector-filter/
├── multiselector-filter.html          # Standalone HTML version
├── multiselector-filter-wp.php        # WordPress plugin main file
├── README.md                          # This file
├── assets/
│   ├── css/
│   │   └── multiselector-filter.css   # Styles
│   └── js/
│       └── multiselector-filter.js    # JavaScript functionality
```

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## License

This project is open source and available under the MIT License.

## Support

For issues or questions, please create an issue on the GitHub repository or contact the developer.
