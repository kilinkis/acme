<?php
/*
 *  Author: Todd Motto | @toddmotto
 *  URL: html5blank.com | @html5blank
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
    External Modules/Files
\*------------------------------------*/

// Register custom navigation walker
require_once('libs/wp_bootstrap_navwalker.php');

/*------------------------------------*\
    Theme Support
\*------------------------------------*/

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Localisation Support
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');
}

/*------------------------------------*\
    Functions
\*------------------------------------*/

// HTML5 Blank navigation
function html5blank_nav()
{
    wp_nav_menu(
    array(
        'theme_location'  => 'header-menu',
        'menu'            => '',
        'container'       => 'div',
        'container_class' => 'menu-{menu slug}-container',
        'container_id'    => '',
        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul>%3$s</ul>',
        'depth'           => 0,
        'walker'          => ''
        )
    );
}

// Load HTML5 Blank scripts (header.php)
function html5blank_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

        wp_register_script('html5blankscripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0.0'); // Custom scripts
        wp_enqueue_script('html5blankscripts'); // Enqueue it!
    }
}

// Load HTML5 Blank conditional scripts
function html5blank_conditional_scripts()
{
    if (is_page('pagenamehere')) {
        wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0'); // Conditional script(s)
        wp_enqueue_script('scriptname'); // Enqueue it!
    }
}

// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'html5blank'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'html5blank'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'html5blank') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'html5blank') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ( 'div' == $args['style'] ) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ( 'div' != $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php endif; ?>
    <div class="comment-author vcard">
    <?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
    <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
    </div>
<?php if ($comment->comment_approved == '0') : ?>
    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
    <br />
<?php endif; ?>

    <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
        <?php
            printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
        ?>
    </div>

    <?php comment_text() ?>

    <div class="reply">
    <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </div>
    <?php if ( 'div' != $args['style'] ) : ?>
    </div>
    <?php endif; ?>
<?php }

/*------------------------------------*\
    Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'html5blank_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'html5blank_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

/*------------------------------------*\
    Custom Post Types
\*------------------------------------*/

// Register Custom Post Type
add_action( 'init', function() {

    $labels = array(
        'name'                  => _x( 'Team Members', 'Post Type General Name', 'sage' ),
        'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'sage' ),
        'menu_name'             => __( 'Team Members', 'sage' ),
        'name_admin_bar'        => __( 'Team Member', 'sage' ),
        'archives'              => __( 'Team Member Archives', 'sage' ),
        'parent_item_colon'     => __( 'Parent Item:', 'sage' ),
        'all_items'             => __( 'All Items', 'sage' ),
        'add_new_item'          => __( 'Add New Item', 'sage' ),
        'add_new'               => __( 'Add New', 'sage' ),
        'new_item'              => __( 'New Item', 'sage' ),
        'edit_item'             => __( 'Edit Item', 'sage' ),
        'update_item'           => __( 'Update Item', 'sage' ),
        'view_item'             => __( 'View Item', 'sage' ),
        'search_items'          => __( 'Search Item', 'sage' ),
        'not_found'             => __( 'Not found', 'sage' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'sage' ),
        'featured_image'        => __( 'Team Member picture', 'sage' ),
        'set_featured_image'    => __( 'Set Team Member picture', 'sage' ),
        'remove_featured_image' => __( 'Remove Team Member picture', 'sage' ),
        'use_featured_image'    => __( 'Use as Team Member picture', 'sage' ),
        'insert_into_item'      => __( 'Insert into team member', 'sage' ),
        'uploaded_to_this_item' => __( 'Uploaded to this team member', 'sage' ),
        'items_list'            => __( 'Items list', 'sage' ),
        'items_list_navigation' => __( 'Items list navigation', 'sage' ),
        'filter_items_list'     => __( 'Filter items list', 'sage' ),
    );
    $args = array(
        'label'                 => __( 'Team Member', 'sage' ),
        'description'           => __( 'Team Members', 'sage' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'menu_icon'           => 'dashicons-groups',
        /*
        
        if for some reason the CPT name wouldn't fit for the permalink structure, it can be overwritten with this code:

        'rewrite' => array(
                'slug' => 'team', 
                'with_front' => true
            )
        */
    );
    register_post_type( 'team', $args );

});

/*------------------------------------*\
    ShortCode Functions
\*------------------------------------*/

add_shortcode( 'meet_the_team', 'display_custom_post_type' );
function display_custom_post_type(){
    $args = array(
        'post_type' => 'team',
        'post_status' => 'publish',
        'posts_per_page' => '2'
    );

    $output = '<section class="team"><div class="container"><div class="row">';

    $query = new WP_Query( $args );
    if( $query->have_posts() ){
        $output .= '<h2>Meet the team</h2>';
        while( $query->have_posts() ){
            $query->the_post();
            $output .= '<div class="member col-sm-6"><div class="row"><div class="col-xs-4"><figure>';

            if ( has_post_thumbnail() ) {
                //$output .= the_post_thumbnail();
                $thumb_id = get_post_thumbnail_id();
                $thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);
                $output .= '<img src="'.$thumb_url[0].'"" />';
            }
            else {
                $output .= '<img src="' . get_bloginfo( 'stylesheet_directory' ) . '/img/gravatar.jpg" />';
            }

            $team_member_name="<h3>" . get_the_title(). "</h3>";
            $output .= '</figure></div><div class="col-xs-8">'. $team_member_name . '<p>'. get_the_excerpt() .'<p><a href="'.get_permalink().'" class="btn-link">Read more</a></div></div></div>';

        }
        //$output .= '</ul>';
    }else{
        // no posts found
        $output .= 'Hello lone wolf, it seems you have no team members yet.';
    }

    $output .= '</div></div></section>';

    wp_reset_postdata();
    return html_entity_decode($output);
}

/*------------------------------------*\
    Additional scripts and styles
\*------------------------------------*/
function acme_enqueuing() {
    wp_register_style ('bootstrapcss', get_template_directory_uri() . '/libs/bootstrap.min.css', array(), '3.3.1', 'all' );
    wp_enqueue_style  ('bootstrapcss');
    wp_register_style ('html5blank', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style  ('html5blank');
    wp_register_script('jquerycdn', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js', array(''), '1.11.1', true); 
    wp_enqueue_script ('jquerycdn'); 
    wp_register_script('bootstrapjs', get_template_directory_uri() . '/libs/bootstrap.min.js', array('jquery'),'3.3.1', true);
    wp_enqueue_script ('bootstrapjs');
}
add_action( 'wp_enqueue_scripts', 'acme_enqueuing' );


/*------------------------------------*\
    Custom logo for login screen
\*------------------------------------*/
function custom_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/img_logo.png);
            padding-bottom: 30px;
            background-size: 188px;
            height: 43px;
            width: 188px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'custom_login_logo' );

/*------------------------------------*\
    Custom metaboxes
\*------------------------------------*/

// add hero title metabox for home template
add_action('add_meta_boxes', 'add_hero_text_meta');
function add_hero_text_meta(){
    global $post;
    if(!empty($post)){
        $pageTemplate = get_post_meta($post->ID, '_wp_page_template', true);

        if($pageTemplate == 'template-home.php' ){
            add_meta_box(
                'hero_text_meta', // $id
                'Hero text', // $title
                'display_hero_text_markup', // $callback
                'page', // $page
                'normal', // $context
                'high'); // $priority
        }
    }
}

// html for meta box
function display_hero_text_markup($object){
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    ?>
        <div>
            <label for="meta-box-title">Title</label>
            <input name="meta-box-title" type="text" size="60" value="<?php echo get_post_meta($object->ID, "meta-box-title", true); ?>">
            <br>
            <label for="meta-box-subtitle ">Subtitle</label>
            <input name="meta-box-subtitle" type="text" size="60" value="<?php echo get_post_meta($object->ID, "meta-box-subtitle", true); ?>">
            <br>
            <label for="meta-box-title-after">Title After Hero Area</label>
            <input name="meta-box-title-after" type="text" size="60" value="<?php echo get_post_meta($object->ID, "meta-box-title-after", true); ?>">
        </div>
    <?php  
}

// save cycle for metabox
function save_custom_meta_box($post_id, $post, $update){
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "page";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";
    $meta_box_dropdown_value = "";
    $meta_box_checkbox_value = "";

    if(isset($_POST["meta-box-title"])){
        $meta_box_text_value = $_POST["meta-box-title"];
    }   
    update_post_meta($post_id, "meta-box-title", $meta_box_text_value);

    if(isset($_POST["meta-box-subtitle"])){
        $meta_box_text_value = $_POST["meta-box-subtitle"];
    }   
    update_post_meta($post_id, "meta-box-subtitle", $meta_box_text_value);

    if(isset($_POST["meta-box-title-after"])){
        $meta_box_text_value = $_POST["meta-box-title-after"];
    }   
    update_post_meta($post_id, "meta-box-title-after", $meta_box_text_value);

}
add_action("save_post", "save_custom_meta_box", 10, 3);



/*------------------------------------*\
    TinyMCE extra button to add button style to a normal link
\*------------------------------------*/

// Callback function to insert 'styleselect' into the $buttons array
function my_mce_buttons_2( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
add_filter('mce_buttons_2', 'my_mce_buttons_2');

// Callback function to filter the MCE settings
function my_mce_before_init_insert_formats( $init_array ) {  
    // Define the style_formats array
    $style_formats = array(  
        // Each array child is a format with it's own settings
        array(  
            'title' => 'Add button style',  
            'selector' => 'a',  
            'classes' => 'btn'             
        )
    );  
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );  

    return $init_array;  

} add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );



