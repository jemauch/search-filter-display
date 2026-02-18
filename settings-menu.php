<?php

function search_filter_config_page() { ?>  
<div class="wrap">
<h2>Staff Details</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'search-filter-settings-group' ); ?>
    <?php do_settings_sections( 'search-filter-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Filter Name</th>
        <td><input type="text" name="filter_name" value="<?php echo esc_attr( get_option('filter_name') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Fields Returned</th>
        <td><input type="text" name="fields_returned" value="<?php echo esc_attr( get_option('fields_returned') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Accountant Email</th>
        <td><input type="text" name="results_per_page" value="<?php echo esc_attr( get_option('results_per_page') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
  </div> <?php } ?>

