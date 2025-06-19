<?php
/**
 * Modal body by package id
 *
 */

if( empty(  $package_data ) ) {
    _e( 'Package not exist! please try again or open sticky. Thank you!', 'woozio' );
    return;
}
?>

<div class="ip-import-steps-container">
    <!-- <pre>
        <?php // print_r( $import_steps ); ?>
    </pre> -->
    <?php foreach( $import_steps as $index => $step ) : ?>
    <?php $active = ( 0 == $index ) ? '__active' : ''; ?>
    <div class="ip-step step-func-<?php echo esc_attr( $step['name'] ), ' ', $active; ?>">
        <?php
        if( function_exists( $step['template_callback'] ) ) {
            call_user_func_array( $step['template_callback'], [$package_data, $step, $index] );
        }
        ?>
    </div>
    <?php endforeach; ?>
</div>
