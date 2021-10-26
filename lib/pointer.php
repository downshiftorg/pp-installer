<?php

/**
 * Initialize installer theme admin menu item pointer
 *
 * @return void
 */
function p8i_pointer_init() {
   $userId = get_current_user_id();
   $dismissed = explode(',', (string) get_user_meta($userId, 'dismissed_wp_pointers', true));

   if (in_array('ppi_pointer', $dismissed)) {
      return;
   }

   wp_enqueue_script('wp-pointer');
   wp_enqueue_style('wp-pointer');
   wp_enqueue_script('ppi_pointer', P8I_URL . 'js/pointer.js');

   wp_localize_script('ppi_pointer', 'ppi_pointer', array(
      'content' => p8i_pointer_markup(),
      'target' => '.toplevel_page_prophoto-installer > a',
      'position' => array(
         'edge' => 'left',
         'align' => 'middle',
      )
   ));
}

/**
 * Get markup for admin pointer
 *
 * @return string
 */
function p8i_pointer_markup() {
   $markup  = '<h3>ProPhoto Installer</h3>';
   $markup .= '<p>Click here to download, test-drive, and activate ProPhoto 8!</p>';
   return $markup;
}
