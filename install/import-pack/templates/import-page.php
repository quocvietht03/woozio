<?php
/**
 * Import pack page panel template
 *
 * @package Import Pack
 * @author BePlus
 */

if( count( $tabs ) <= 0 ) return;
?>
<div id="Import_Pack_Container">

    <h1 class="heading-page"><?php echo apply_filters( 'woozio/import_pack/heading_text', __( 'Woozio Import Package Demo.', 'woozio' ) ); ?></h1>

    <?php do_action( 'woozio/import_pack/before' ); ?>

    <div class="inner-page">
        <div class="ip-tab-container">
            <ul class="ip-tab-heading">
                <?php foreach( $tabs as $index => $tab ) : ?>
                <li id="tab_<?php echo esc_attr( $tab['id'] ); ?>" class="ip-tab-heading-item">
                    <a href="#<?php echo esc_attr( $tab['id'] ); ?>" data-tab-id="<?php echo esc_attr( $tab['id'] ); ?>"><?php echo "{$tab['title']}" ?></a>
                </li> <!-- #tab_<?php echo esc_attr( $tab['id'] ); ?> -->
                <?php endforeach; ?>

                <li class="__open-ticket">
                    <a href="<?php echo esc_url( IMPORT_URL_OPEN_TICKET ); ?>" target="_blank">
                        <span class="dashicons dashicons-editor-help"></span>
                        <?php _e( 'Open Ticket', 'woozio' ); ?>
                    </a>
                </li>
            </ul> <!-- .ip-tab-heading -->

            <div class="ip-tab-body">
                <?php foreach( $tabs as $index => $tab ) : ?>
                <div id="tab_body_<?php echo esc_attr( $tab['id'] ); ?>">
                    <?php
                        if( function_exists( $tab['template_callback'] ) )
                            call_user_func( $tab['template_callback'] )
                    ?>
                </div> <!-- #tab_body_<?php echo esc_attr( $tab['id'] ); ?> -->
                <?php endforeach; ?>
            </div> <!-- .ip-tab-body -->
        </div>
    </div>

    <div class="ip-footer">
        <?php echo apply_filters( 'woozio/import_pack/footer_text', __( 'Power by Beplus, Thank you creating with Woozio.', 'woozio' ) ); ?>
    </div>

    <?php do_action( 'woozio/import_pack/after' ); ?>

</div> <!-- #Import_Pack_Container -->

<div id="Import_Pack_Modal" class="ip-modal-container">
    <div class="ip-modal">
        <div class="ip-modal-header">
            <a href="javascript:" class="__close" title="<?php echo esc_attr( 'close', 'woozio' ); ?>">
                <svg x="0px" y="0px" viewBox="0 0 357 357" style="enable-background:new 0 0 357 357;" xml:space="preserve"> <g> <g id="close"> <polygon points="357,35.7 321.3,0 178.5,142.8 35.7,0 0,35.7 142.8,178.5 0,321.3 35.7,357 178.5,214.2 321.3,357 357,321.3 214.2,178.5 "/> </g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>
            </a>
        </div>
        <div class="ip-modal-body"></div>
    </div>
</div> <!-- #Import_Pack_Modal -->
