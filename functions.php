<?php
function enqueue_select2_jquery()
{
  wp_register_style('select2css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
  wp_register_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array('jquery'), '4.0.13', true);
  wp_enqueue_style('select2css');
  wp_enqueue_script('select2');

  // Add inline script
  $inline_script = 'jQuery(document).ready(function($) {
        $("#hcf_grow_dif").select2();
    });';
  wp_add_inline_script('select2', $inline_script);
}
add_action('wp_enqueue_scripts', 'enqueue_select2_jquery');



add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles()
{
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
  wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'), wp_get_theme()->get('Version'));
}

function prefix_add_content($content)
{
  $custom_fields_aka_ratings_type = array(
    'hcf_aka',
    'hcf_user_ratings',
    'hcf_strain_type'
  );
  $custom_fields_dom_terp = array(
    'hcf_dom_terp'
  );
  $custom_fields_other_terp = array(
    'hcf_other_terp_1',
    'hcf_other_terp_2'
  );
  $custom_fields_thc_cbg_cbd = array(
    'hcf_THC',
    'hcf_CBG',
    'hcf_CBD'
  );
  $custom_fields_flav = array(
    'hcf_flav_1',
    'hcf_flav_2',
    'hcf_flav_3'
  );
  $custom_fields_feel = array(
    'hcf_feel_1',
    'hcf_feel_2',
    'hcf_feel_3'
  );
  $custom_fields_help = array(
    'hcf_help_1',
    'hcf_help_2',
    'hcf_help_3'
  );
  $custom_fields_neg = array(
    'hcf_neg_1',
    'hcf_neg_2',
    'hcf_neg_3'
  );
  $custom_fields_grow = array(
    'hcf_grow_dif',
    'hcf_grow_avg_hight',
    'hcf_grow_avg_yeild',
    'hcf_grow_time'
  );
  $custom_fields_parent_child = array(
    'hcf_parent_1',
    'hcf_parent_2',
    'hcf_child_1',
    'hcf_child_2'
  );

  if (esc_attr(get_post_meta(get_the_ID(), 'hcf_strain_type', true))) {
    $title = get_the_title();
    $terp_profile = '<h3>' . $title . ' Terpene Profile</h3>';
    $info = '<h3>' . $title . ' Info</h3>';
    $flavs = '<h3>' . $title . ' Flavors</h3>';
    $feels = '<h3>' . $title . ' Feelings</h3>';
    $may_help = '<h3>' . $title . ' May help with</h3>';
    $neg = '<h3>' . $title . ' Possible Negatives</h3>';
    $genetics = '<h3>' . $title . ' Genetics</h3>';
    $grow = '<h3>' . $title . ' Grow Info</h3>';
    $seed_link = "<a href=" . get_post_meta(get_the_ID(), 'hcf_seed_link', true) . ">Find " . $title . " Seed Here</a>";
  }
  ;


  // More custom field arrays as needed...
  $table_aka = prefix_generate_table($custom_fields_aka_ratings_type);
  
  
  $table_thc_cbg_cbd = prefix_generate_table($custom_fields_thc_cbg_cbd);
  $table_dom_terp = prefix_generate_table($custom_fields_dom_terp);
  $table_other_terp = prefix_generate_table($custom_fields_other_terp);

  // Add original post content in between the tables
  $new_content = '';
  $new_content .= $content;
  $table_flav = prefix_generate_table_3($custom_fields_flav);
  $table_feel = prefix_generate_table_3($custom_fields_feel);
  $table_help = prefix_generate_table_3($custom_fields_help);
  $table_neg = prefix_generate_table_3($custom_fields_neg);
  $table_parent_child = prefix_generate_table($custom_fields_parent_child);
  $table_grow = prefix_generate_table($custom_fields_grow);



  // Add more tables as needed...
  $new_content = $table_aka . $table_thc_cbg_cbd . $terp_profile . $table_dom_terp .
    $table_other_terp . $info . $new_content . $flavs . $table_flav . $feels . $table_feel . $may_help . $table_help . $neg . $table_neg . $seed_link . $genetics . $table_parent_child . $grow . $table_grow;

  return $new_content;
}
add_filter('the_content', 'prefix_add_content');


function prefix_generate_table($fields)
{
  $table = '<table><tbody>';

  foreach ($fields as $field) {
    $field_value = get_post_meta(get_the_ID(), $field, true);

    if ($field_value) {
      // Remove 'hcf_' from the field name and capitalize each word
      $field_name = str_replace('hcf_', '', $field);
      $field_name = str_replace('_', ' ', $field_name);
      $field_name = ucwords($field_name);
      $svg = get_svg($field_name); 
      $table .= '<tr><td><strong>' . $svg . $field_name . '</strong></td><td>' . esc_attr($field_value) . '</td></tr>';
    }
  }

  $table .= '</tbody></table>';

  return $table;
}

function prefix_generate_table_3($fields)
{
  $table = '<table><tbody><tr>';

  foreach ($fields as $field) {
    $field_value = get_post_meta(get_the_ID(), $field, true);

    if ($field_value) {
      $svg = get_svg($field_value);
      $table .= '<td>' . $svg . $field_value . '</td>';
    }
  }

  $table .= '</tr></tbody></table>';

  return $table;
}

function get_svg($field_value =''){
  $svg_array = array(
    'sweet' => '<svg class="align-svg" width="20" height="20"><path d="M14.8 8c0-.3-.1-.6-.2-.8-.1-.4-.4-.7-.6-.9-.3-.3-.6-.5-1-.7-.1 0-.2-.1-.3-.1.1-.2.1-.3.3-.4.1-.1.3-.2.4-.3.2-.1.3-.1.5 0 .4.1.8-.2.8-.6.1-.4-.2-.8-.6-.8-.4-.1-.8 0-1.2.1s-.7.3-1 .6c-.3.3-.5.6-.6 1 0 .1-.1.3-.1.5-.1 0-.3.1-.4.1-.3.1-.6.3-.9.5-.3.3-.5.6-.6 1-.1.2-.2.5-.2.8-3 .8-4.8 2.6-4.8 5.1 0 .7.2 1.4.7 2 .3.4.7.7 1.2.9l.7 3.3c.1.4.3.8.7 1 .3.3.7.4 1.2.4h6.5c.4 0 .8-.1 1.2-.4.3-.3.6-.6.7-1l.7-3.3c.5-.2.9-.5 1.2-.9.4-.6.7-1.3.7-2-.2-2.5-2-4.3-5-5.1zm-4.1-.2c.1-.2.2-.3.3-.4.1-.1.3-.2.4-.3.2-.1.3-.1.5-.1s.4 0 .5.1c.2.1.3.2.4.3.1.1.2.3.3.4.1.2.1.3.1.5s0 .4-.1.5c0 .2-.1.3-.2.5-.1.1-.3.2-.4.3-.3.1-.7.1-1.1 0-.2-.1-.3-.2-.4-.3-.1-.1-.2-.3-.3-.4-.1-.2-.1-.3-.1-.5-.1-.3 0-.5.1-.6zM8.4 19.2c-.1 0-.1-.1-.1-.2l-.6-2.7c.4 0 .8-.1 1.1-.3 0 0 .1 0 .1-.1l.5 3.3h-1zm4.5 0H11l-.5-3.2c.5.2 1 .4 1.5.4s1-.1 1.5-.4l-.6 3.2zm2.5 0c-.1.1-.1.1-.2.1h-.8l.5-3.3s.1 0 .1.1c.4.2.7.3 1.1.3l-.6 2.6s0 .1-.1.2zm2.3-5c-.2.3-.6.5-1 .6-.4.1-.8 0-1.1-.1-.4-.2-.6-.4-.8-.8-.1-.3-.4-.4-.7-.4-.3 0-.5.2-.7.4-.1.3-.4.5-.6.7-.5.3-1.3.3-1.8 0-.3-.2-.5-.4-.6-.7-.1-.3-.4-.4-.7-.4-.3 0-.5.2-.7.4-.2.3-.5.6-.8.8-.4.2-.8.2-1.1.1-.4-.1-.7-.3-1-.6s-.4-.7-.4-1.1c0-2.3 2.2-3.2 3.6-3.6.1.3.3.6.6.8.3.3.6.5.9.6.4.1.7.2 1.1.2.4 0 .8-.1 1.1-.2s.7-.4.9-.6c.2-.2.4-.5.6-.8 1.3.4 3.6 1.4 3.6 3.6 0 .4-.1.8-.4 1.1z"></path></svg>',
    'berry' => '<svg class="align-svg" width="20" height="20"><path d="M18.3 13.7c-.5-.8-1.3-1.3-2.2-1.6-.3-.1-.6-.2-.9-.2-.9-2.3 0-4.4.9-6.6.1-.3.3-.6.4-.9.1-.3 0-.6-.1-.8-.2-.2-.5-.3-.8-.2-4.6 1.2-7.4 4.1-8.5 8.7-.5.2-1.1.6-1.5 1-.8.8-1.3 2-1.3 3.2s.5 2.3 1.3 3.2c.8.8 2 1.3 3.2 1.3 1.1 0 2.1-.4 2.9-1.1.5.4 1.1.7 1.7.9.4.1.8.2 1.3.2s1-.1 1.5-.2c.9-.3 1.6-.9 2.2-1.6.5-.8.8-1.7.8-2.6-.1-1.1-.4-2-.9-2.7zm-4-8.4c-.8 2-1.5 4.2-.8 6.7h-.2c-.6.2-1.2.5-1.7.9-.8-.7-1.8-1.1-2.9-1.1.9-3.2 2.8-5.3 5.6-6.5zM6.6 18.4c-.6-.6-.9-1.3-.9-2.1s.3-1.5.9-2.1c.6-.6 1.3-.9 2.1-.9s1.5.3 2.1.9.9 1.3.9 2.1-.3 1.5-.9 2.1c-1.1 1.1-3 1.1-4.2 0zM17 18c-.4.5-.9.9-1.5 1.1-.6.2-1.2.2-1.8 0-.4-.1-.8-.3-1.2-.6.4-.7.6-1.4.6-2.2 0-.8-.2-1.5-.6-2.2.3-.3.7-.5 1.2-.6.6-.2 1.2-.2 1.8 0 .6.2 1.1.6 1.5 1.1.4.5.6 1.1.6 1.7 0 .6-.2 1.2-.6 1.7z"></path></svg>',
    'blueberry' => '<svg viewBox="3 3 18 18" height="34" width="65"><path d="M11.9 7.7c.3 0 1 .2 1.3 1 .1.4.6.6.9.5.4-.1.6-.6.5-.9-.5-1.6-2-2-2.7-2-.4 0-.8.3-.8.8.1.3.4.6.8.6z"></path><path d="M18.9 12.4c-.6-.8-1.4-1.4-2.3-1.7-.1 0-.2-.1-.3-.1.1-.2.1-.3.2-.5.3-.9.3-2-.1-2.9-.3-.9-.9-1.7-1.7-2.3-.8-.6-1.8-.9-2.7-.9-1 0-2 .3-2.8.9-.8.6-1.4 1.4-1.7 2.3-.3.9-.3 1.9-.1 2.9 0 .2.1.3.2.5-.8.2-1.6.6-2.2 1.2-.9.9-1.4 2.1-1.4 3.4s.5 2.5 1.4 3.4S7.5 20 8.8 20c1.2 0 2.3-.4 3.1-1.2.5.5 1.1.8 1.8 1 .9.3 2 .3 2.9-.1.9-.3 1.7-.9 2.3-1.7.6-.8.9-1.8.9-2.8s-.3-2-.9-2.8zm-10-4.7c.2-.7.6-1.2 1.2-1.6s1.2-.6 1.9-.6 1.3.2 1.9.6 1 .9 1.2 1.6c.2.6.2 1.3 0 2-.1.3-.2.5-.3.8-.4 0-.7.1-1 .2-.7.2-1.3.5-1.8 1-.8-.7-1.7-1.1-2.7-1.2-.3-.3-.4-.6-.5-.8-.1-.7-.1-1.4.1-2zm2.1 9.8c-.6.6-1.4 1-2.3 1s-1.7-.3-2.3-1c-.6-.6-1-1.4-1-2.3 0-.9.3-1.7 1-2.3.6-.6 1.4-1 2.3-1s1.7.3 2.3 1c.6.6 1 1.4 1 2.3 0 .8-.4 1.7-1 2.3zm6.7-.5c-.4.5-.9 1-1.6 1.2-.6.2-1.3.2-2 0-.5-.1-.9-.4-1.3-.7.4-.7.7-1.5.7-2.4 0-.9-.2-1.7-.7-2.4.4-.3.8-.6 1.3-.7.6-.2 1.3-.2 2 0 .6.2 1.2.6 1.6 1.2.4.5.6 1.2.6 1.9s-.2 1.4-.6 1.9z"></path><path d="M16.9 14.2c-.4 0-.8.3-.8.8 0 .8-.3 1.1-.6 1.2-.3.2-.7.2-.9.1-.4-.2-.8 0-1 .4-.2.4 0 .8.4 1 .6.2 1.5.2 2.2-.2.8-.4 1.3-1.3 1.3-2.5.2-.5-.1-.8-.6-.8zm-6.5 0c-.4 0-.8.3-.8.8 0 .8-.3 1.1-.6 1.2-.3.2-.7.2-.9.1-.4-.2-.8 0-1 .4-.2.4 0 .8.4 1 .6.2 1.5.2 2.2-.2.8-.4 1.3-1.3 1.3-2.5.2-.5-.1-.8-.6-.8z"></path></svg>',
    'hcf_flav_3' => '<svg class="align-svg" width="20" height="20"><!-- SVG code for flavor 3 goes here --></svg>',
    'THC' => '<svg width="20" height="20"><path fill-rule="evenodd" clip-rule="evenodd" d="M15.523 9.648L9.648 3.773 3.773 9.648l5.875 5.875 5.875-5.875zM9.648.944L.945 9.648l8.703 8.704 8.704-8.704L9.648.944z" fill="#38C7AE"></path></svg> % ',
    'CBD' => '<svg width="20" height="20"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.73 4.044a6.039 6.039 0 108.54 8.54 6.039 6.039 0 00-8.54-8.54zm9.927-1.387A8 8 0 102.343 13.97 8 8 0 0013.657 2.657z" fill="#38C7AE"></path></svg>  % ',
    'CBG' => '<svg width="20" height="20"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.907 15L19 .75H.813L9.907 15zm0-3.718L15.35 2.75H4.462l5.445 8.532z" fill="#38C7AE"></path></svg>  % ',
    'CBN' => '<svg width="20" height="20"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.907 15L19 .75H.813L9.907 15zm0-3.718L15.35 2.75H4.462l5.445 8.532z" fill="#38C7AE"></path></svg>  % ',
  );
  if(isset($svg_array[$field_value])){
    return $svg_array[$field_value];
  }
  return '';
}