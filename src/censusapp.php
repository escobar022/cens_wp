<?php

/**
 * Main censusApp Class
 *
 * @class censusApp
 * @version    1.0.0
 */
class censusApp
{

    /**
     * censusApp Constructor
     */
    public function __construct()
    {
        $this->registerPostType();
        add_action('add_meta_boxes', array($this, 'add_custom_meta_box'));
        add_action('save_post', array($this, 'save_custom_meta'));

        wp_register_script('wp_quiz_main_js', pp() . '/js/custom-js.js', array('jquery', 'jquery-ui-sortable'), null, true);
        wp_enqueue_script('wp_quiz_main_js');
    }

    /**
     * Meta Fields for view
     */
    function custom_meta_fields()
    {
        $prefix = 'census_';
        $custom_meta_fields = array(
            array(
                'label' => 'Census API Key ',
                'desc' => 'Please enter API Key here',
                'id' => $prefix . 'ape_text',
                'type' => 'text'
            ),
            array(
                'label' => 'Select State',
                'desc' => 'Please select a state.',
                'id' => $prefix . 'state_select',
                'type' => 'select'
            ),
            array(
                'label' => 'Housing variables',
                'desc' => 'Please enter the housing variables, for more info <a href="http://api.census.gov/data/2010/sf1/variables.html" target="_blank">Census Variables</a>.',
                'id' => $prefix . '_hs_variables',
                'type' => 'repeatable'
            )
        );
        return $custom_meta_fields;
    }

    /**
     * Register census_view post type.
     */
    function registerPostType()
    {
        $labels = array(
            'name' => _x('Census View', 'post type general name'),
            'singular_name' => _x('Census View', 'post type singular name'),
            'menu_name' => _x('Census Views', 'admin menu'),
            'name_admin_bar' => _x('Census View', 'add new on admin bar'),
            'add_new' => _x('Add New', 'book'),
            'add_new_item' => __('Add New Census View'),
            'new_item' => __('New Census View'),
            'edit_item' => __('Edit Census View'),
            'view_item' => __('View Census View'),
            'all_items' => __('All Census Views'),
            'search_items' => __('Search Census Views'),
            'parent_item_colon' => __('Parent Census Views:'),
            'not_found' => __('No census views found.'),
            'not_found_in_trash' => __('No census views found in Trash.')
        );

        $args = array(
            'labels' => $labels,
            'description' => __('Description.'),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'author')
        );

        register_post_type('census_view', $args);
    }

    /**
     * Add Custom Meta Box
     */
    function add_custom_meta_box()
    {
        add_meta_box(
            'custom_meta_box', // $id
            'Custom Meta Box', // $title
            array($this, 'show_custom_meta_box'), // $callback
            'census_view', // $page
            'normal', // $context
            'high'); // $priority
    }

    /**
     * Show Custom Meta Box
     */
    function show_custom_meta_box()
    {
        global $post;

        $states = array(
            '' => 'Select a State',
            '01' => 'Alabama',
            '02' => 'Alaska',
            '04' => 'Arizona',
            '05' => 'Arkansas',
            '06' => 'California',
            '08' => 'Colorado',
            '09' => 'Connecticut',
            '10' => 'Delaware',
            '11' => 'Washington, D . C .',
            '12' => 'Florida',
            '13' => 'Georgia',
            '15' => 'Hawaii',
            '16' => 'Idaho',
            '17' => 'Illinois',
            '18' => 'Indiana',
            '19' => 'Iowa',
            '20' => 'Kansas',
            '21' => 'Kentucky',
            '22' => 'Louisiana',
            '23' => 'Maine',
            '24' => 'Maryland',
            '25' => 'Massachusetts',
            '26' => 'Michigan',
            '27' => 'Minnesota',
            '28' => 'Mississippi',
            '29' => 'Missouri',
            '30' => 'Montana',
            '31' => 'Nebraska',
            '32' => 'Nevada',
            '33' => 'New Hampshire',
            '34' => 'New Jersey',
            '35' => 'New Mexico',
            '36' => 'New York',
            '37' => 'North Carolina',
            '38' => 'North Dakota',
            '39' => 'Ohio',
            '40' => 'Oklahoma',
            '41' => 'Oregon',
            '42' => 'Pennsylvania',
            '44' => 'Rhode Island',
            '45' => 'South Carolina',
            '46' => 'South Dakota',
            '47' => 'Tennessee',
            '48' => 'Texas',
            '49' => 'Utah',
            '50' => 'Vermont',
            '51' => 'Virginia',
            '53' => 'Washington',
            '54' => 'West Virginia',
            '55' => 'Wisconsin',
            '56' => 'Wyoming',
            '60' => 'American Samoa',
            '66' => 'Guam',
            '72' => 'Puerto Rico',
            '78' => 'Virgin Islands',
        );

        // Use nonce for verification
        echo '<input type="hidden" name="custom_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '" />';

        // Begin the field table and loop
        echo '<table class="form-table">';
        foreach ($this->custom_meta_fields() as $field) {
            // get value of this field if it exists for this post
            $meta = get_post_meta($post->ID, $field['id'], true);
            // begin a table row with
            echo '<tr>
                <th><label for="' . $field['id'] . '">' . $field['label'] . '</label></th>
                <td>';
            switch ($field['type']) {
                // case items will go here
                // text
                case 'text':
                    echo '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta . '" size="30" /> <br /><span class="description">' . $field['desc'] . '</span>';
                    break;
                case 'select':
                    echo '<select name="' . $field['id'] . '" id="' . $field['id'] . '">';
                    foreach ($states as $key => $value) {
                        echo '<option', $meta == $key ? ' selected="selected"' : '', ' value="' . $key . '">' . $value . '</option>';
                    }
                    echo '</select><br /><span class="description">' . $field['desc'] . '</span>';
                    break;
                // repeatable
                case 'repeatable':
                    echo '<a id="repeatable-add" class="button" href="#">+</a><ul id="' . $field['id'] . '-repeatable" class="custom_repeatable">';
                    $i = 0;
                    if ($meta) {
                        foreach ($meta as $row) {
                            echo '<li><span class="sort hndle">|||</span><input type="text" name="' . $field['id'] . '[' . $i . ']" id="' . $field['id'] . '" value="' . $row . '" size="30" /><a class="repeatable-remove button" href="#">-</a></li>';
                            $i++;
                        }
                    } else {
                        echo '<li><span class="sort hndle">|||</span>
                    <input type="text" name="' . $field['id'] . '[' . $i . ']" id="' . $field['id'] . '" value="" size="30" />
                    <a class="repeatable-remove button" href="#">-</a></li>';
                    }
                    echo '</ul>
        <span class="description">' . $field['desc'] . '</span>';
                    break;
            } //end switch
            echo '</td></tr>';
        } // end foreach
        echo '</table>'; // end table
    }

    /**
     * Save Custom Meta Box
     */
    function save_custom_meta($post_id)
    {
        global $post;

        // verify nonce
        if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
            return $post_id;
        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        // check permissions
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id))
                return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        // loop through fields and save the data
        foreach ($this->custom_meta_fields() as $field) {
            $old = get_post_meta($post_id, $field['id'], true);
            $new = $_POST[$field['id']];
            if ($new && $new != $old) {
                update_post_meta($post_id, $field['id'], $new);
            } elseif ('' == $new && $old) {
                delete_post_meta($post_id, $field['id'], $old);
            }
        } // end foreach
        return true;
    }



}