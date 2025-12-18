/**
 * Import pack javascript
 *
 * @package Import Pack
 * @author Bearsthemes
 */

/**
 * import_pack_php_data
 *  - ajax_url
 */

; ( function( w, $ ) {
    'use strict';

    /**
     * Serialize form data to JSON
     *
     */
    $.fn.ipSerializeObject=function(){var o={};var a=this.serializeArray();$.each(a,function(){if(o[this.name]){if(!o[this.name].push){o[this.name]=[o[this.name]]}
    o[this.name].push(this.value||'')}else{o[this.name]=this.value||''}});return o}

    var ImportPack = {},
        Modal = null,
        Current_Package_Id = null;

    ImportPack.Modal = function( args ) {

        var modal_element = $( '#Import_Pack_Modal' );

        modal_element.on( {
            '__open.modal' ( e ) {
                $( 'body' ).addClass( '_import-pack-modal-open' );
            },
            '__close.modal' ( e ) {
                $( 'body' ).removeClass( '_import-pack-modal-open' );
            },
            '__loading.modal' ( e, enable ) {

                if( true == enable ) {
                    modal_element.addClass( '__is-loading' );
                } else {
                    modal_element.removeClass( '__is-loading' );
                }
            },
            '__update_content.modal' ( e, content ) {

                modal_element.find( '.ip-modal-body' ).empty().append( content );
            }
        } )

        modal_element.on( 'click', '.__close', function( e ) {
            e.preventDefault();
            modal_element.trigger( '__close.modal' );
        } )

        return function() {
            return {
                $el: modal_element,
                open () {
                    modal_element.trigger( '__open.modal' )
                    return this;
                },
                close () {
                    modal_element.trigger( '__close.modal' )
                    return this;
                },
                loading ( enable ) {
                    modal_element.trigger( '__loading.modal', [enable] );
                    return this;
                },
                udpate_content ( content ) {
                    modal_element.trigger( '__update_content.modal', [content] );
                    return this;
                }
            };
        } ()
    }

    ImportPack.LoadImportTemplate = async function( package_id ) {

        try {

            var result = await $.ajax( {
                type: 'POST',
                url: import_pack_php_data.ajax_url,
                data: {
                    action: 'woozio_import_pack_modal_import_body_template',
                    package_id: package_id,
                    template_nonce: import_pack_php_data.template_nonce,
                },
            } )

            return result;

        } catch( error ) {
            alert( 'Error 1: Internal error, Please try again or open ticket!' );
        }
    }

    ImportPack.ImportTemplateAddTriggerEvents = function( elem, package_id ) {

        elem.on( {
            '__next_step.import' ( e ) {

                var current_step = elem.find( '.ip-step.__active' );
                var next_step = current_step.next();

                next_step.addClass( '__active' ).siblings().removeClass( '__active' );
            },
        } )

        var action_form_handle = function( data, response ) {

            var actions_map = {
                __next_step__ ( response ) {
                    elem.trigger( '__next_step.import' );
                }
            }

            actions_map[data].call( data, response );
        }

        elem.on( 'click', '.actions *[data-type]', function( e ) {
            e.preventDefault();

            var action_type = $( this ).data( 'type' );
            var form_data =  $( this ).parents( '.actions' ).find( 'form.ip-actions-callback-form' ).ipSerializeObject();
            
            if( ! form_data[action_type] ) {
              return;
            }
            
            $.ajax( {
                type: 'POST',
                url: import_pack_php_data.ajax_url,
                data: { action: 'woozio_import_pack_import_action_ajax_callback', data: {
                    package_id: package_id,
                    action_type: action_type,
                    form_data: form_data,
                    callback_nonce: import_pack_php_data.callback_nonce,
                } },
                success ( response ) {
                    
                    if( true == response.success ) {

                        if( 'success' == response.type ) {
                            if( false == response.result.status ) {
                                alert( response.message );
                                return;
                            }
                            if( response.result.form_action ) { 
                                action_form_handle( response.result.form_action, response ); 
                            }
                        } else {
                            alert( response.message );
                            return;
                        }
                    } else  {
                        alert( response.message );
                        return;
                    }
                },
                error () {
                    alert( 'Demo import failed. Please open a ticket for support!' );

                },
            } )
        } )
    }

    ImportPack.Import = async function( package_id ) {

        // Enable modal import & add mode loading.
        Modal.open().loading( true );

        // get import template
        var load_template_result = await ImportPack.LoadImportTemplate( package_id );

        // Off modal loading
        Modal.loading( false );

        if( true != load_template_result.success ) {
            alert( 'Error 2: Load import step error, Please try again or open ticket!' );
            return;
        }

        var import_template = $( load_template_result.content );

        await ImportPack.ImportTemplateAddTriggerEvents( import_template, package_id );
        await Modal.udpate_content( import_template );


    }

    ImportPack.ExplainedUi = function() {

        $( 'body' ).on( 'click', '.ip-explained-container .__toggle-explained', function( e ) {
            e.preventDefault();

            var explained_content = $( this ).parent().find( '.ip-explained-content' );
            explained_content.slideToggle( 'slow' );
        } )
    }

    ImportPack.DownloadPackage = async function( package_name, position, _package, args ) {
        
        var send_data = {
            package_name: package_name,
            position: position || 0,
            package: _package || '',
            download_package_nonce: import_pack_php_data.download_package_nonce,
        };

        try {
            var result = await $.ajax( {
                type: 'POST',
                url: import_pack_php_data.ajax_url,
                data: {
                    action: 'woozio_import_pack_download_package',
                    data: send_data,
                }
            } );
        } catch ( error ) {
            return ImportPack.DownloadPackage( package_name, position, _package, args );
        }


        if( args.after_request_callback ) {
            args.after_request_callback.call( send_data, result );
        }

        if( true == result.success && true != result.result.download_package_success ) {
            await ImportPack.DownloadPackage( package_name, result.result.x_position, result.result.package, args );
        }

        return result;
    }

    ImportPack.ExtractPackage = async function( package_name, _package ) {

        try {

            var result = await $.ajax( {
                type: 'POST',
                url: import_pack_php_data.ajax_url,
                data: {
                    action: 'woozio_import_pack_extract_package_demo',
                    data: {
                        package_name: package_name,
                        package: _package,
                        extract_package_nonce: import_pack_php_data.extract_package_nonce,
                    },
                }
            } )

            return result;
        } catch( error ) {
            alert( `Error: Extract package error!` );
            return;
        }
    }

    ImportPack.ResorePackage = async function( package_path ) {

        try {
            var result = await $.ajax( {
                type: 'POST',
                url: import_pack_php_data.ajax_url,
                data: {
                    action: 'woozio_import_pack_restore_data',
                    data: {
                        package_path: package_path,
                        restore_data_nonce: import_pack_php_data.restore_data_nonce,
                    },
                    nonce: import_pack_php_data.import_nonce.restore_data,
                }
            } )

            return result;
        } catch( error ) {
            alert( `Error: Restore package error!` );
            return;
        }
    }

    ImportPack.CustomActionInstallPackage = function() {

        $( 'body' ).on( 'click', '#Import_Pack_Modal .ip-step.step-func-download_import_package .btn-action-yes', async function( e ) {
            e.preventDefault();

            $( this ).hide();

            var step_container = $( '.ip-import-steps-container' );
            var log = $( `<span class="__message-log"></span>` );
            step_container.find( '.step-func-download_import_package .actions' ).prepend( log );

            // Download package
            var package_size_total = 0;
            log.html( `Start download package...` );

            var download_package_result = await ImportPack.DownloadPackage( Current_Package_Id, 0, '', {
                after_request_callback ( result ) {

                    if( true == result.success && result.result.package_size ) {
                        package_size_total = parseFloat( result.result.package_size );
                    }

                    log.html( `Downloading ${parseFloat( result.result.package_download )} of ${package_size_total} Mb ...` );
                }
            } );

            // Extract package
            log.html( `Extract package...` );
            var extract_package_result = await ImportPack.ExtractPackage( Current_Package_Id, 'package-demo.zip' );

            if( true == extract_package_result.success && true == extract_package_result.result.extract_success ) {
                log.html( `Extract package successful!` );
            }

            // Restore package
            log.html( `Restore package...` );
            var restore_package_result = await ImportPack.ResorePackage( extract_package_result.result.extract_to );

            if( true == restore_package_result.success && true == restore_package_result.result.restore ) {
                log.html( `Restore package successful!` );
                step_container.trigger( '__next_step.import' );
            }
        } )
    }

    ImportPack.CustomActionInstallPackageSuccessful = function() {

        $( 'body' ).on( 'click', '.step-func-import_package_successful .button-close', function( e ) {
            e.preventDefault();
            Modal.close();
        } )
    }

    ImportPack.CustomActionBackupSite = function() {

        var do_sub_steps = async function( args ) {

            var sub_step = $( '.step-func-backup_site .__sub-step > li.__step' );
            if( sub_step.length <= 0 ) {
                step_container.trigger( '__next_step.import' );
                return;
            }

            var done = true;
            var next_step_data = {};
            for( var i = 0; i <= sub_step.length - 1; i++ ) {

                var step_item = sub_step.eq( i );
                var step_name = step_item.data( 'step-name' );

                step_item.addClass( '__loading' );


                var result = await $.ajax( {
                    type: 'POST',
                    url: import_pack_php_data.ajax_url,
                    data: {
                        action: `woozio_import_pack_backup_site_substep_${step_name}`,
                        data: {
                            next_step_data: next_step_data,
                            backup_site_nonce: import_pack_php_data.backup_site_nonce,
                        },
                        nonce: import_pack_php_data.import_nonce[step_name],
                    }
                } );

                if( args.step_callback ) {
                    args.step_callback.call( step_item, result );
                }

                step_item.removeClass( '__loading' );

                if( true == result.success && true == result.result.status ) {

                  next_step_data = ( result.result.next_step_data ) ? result.result.next_step_data : {};
                  step_item.addClass( '__success' );

                } else {
                    if( 'install_bears_backup_plg' == step_name && false == result.result.status ) {

                      result = await $.ajax( {
                          type: 'POST',
                          url: import_pack_php_data.ajax_url,
                          data: {
                              action: `woozio_import_pack_backup_site_substep_${step_name}`,
                              data: {
                                    next_step_data: next_step_data,
                                    backup_site_nonce: import_pack_php_data.backup_site_nonce,
                              },
                          }
                      } );

                    } else {
                      step_item.addClass( '__fail' );
                      done = false;

                      alert( 'Backup site fail! Please try again or contact our support team. Thank you!' )
                      break;
                    }
                }
            }

            return done;
        }

        $( 'body' ).on( 'click', '.step-func-backup_site .actions .btn-action-skip', async function( e ) {
            e.preventDefault();

            $( this ).hide();

            var step_container = $( '.ip-import-steps-container' );
            var log = $( `<span class="__message-log"></span>` );

            step_container.find( '.step-func-backup_site .actions' ).find( '.btn-action-yes' ).remove();
            step_container.find( '.step-func-backup_site .actions' ).prepend( log );

            log.html( `Skipping...` );

        } )

        $( 'body' ).on( 'click', '.step-func-backup_site .actions .btn-action-yes', async function( e ) {
            e.preventDefault();

            $( this ).hide();

            var step_container = $( '.ip-import-steps-container' );
            var log = $( `<span class="__message-log"></span>` );

            step_container.find( '.step-func-backup_site .actions' ).find( '.btn-action-skip' ).remove();
            step_container.find( '.step-func-backup_site .actions' ).prepend( log );

            log.html( `Start backup site...` );

            var $backup_result = await do_sub_steps( {
                step_callback ( result ) {
                    log.html( result.message );
                }
            } );

            if( true == $backup_result ) {
                log.html( `Backup site successful.` );
                step_container.trigger( '__next_step.import' );
            }

        } )
    }

    /**
     * DOM ready
     */
    $( function() {

        Modal = new ImportPack.Modal();
        ImportPack.ExplainedUi();
        ImportPack.CustomActionBackupSite();
        ImportPack.CustomActionInstallPackage();
        ImportPack.CustomActionInstallPackageSuccessful();

        $( 'body' ).on( 'click', '.__action-import-demo[data-package-id]', function( e ) {
            e.preventDefault();
            var package_id = $( this ).data( 'package-id' );
            Current_Package_Id = package_id;

            ImportPack.Import( package_id )
        } )
    } )
    
    /* Tabs Demo */
    $( '#tab_demo_install_package' ).addClass('active');
    $( '#tab_body_demo_install_package' ).addClass('active');

    $( '.ip-tab-heading-item' ).on( 'click', 'a', function( e ) {
      e.preventDefault();
      if( $(this).parent().hasClass('active') ) {
        return;
      }
      $( '.ip-tab-heading-item' ).removeClass('active');
      $( '.ip-tab-body > div' ).removeClass('active');
      $(this).parent().addClass('active');
      $('#tab_body_' + $(this).data('tab-id') ).addClass('active');
    });

    /**
     * Browser load competed
     */
    $( w ).load( function() {

    } )

} )( window, jQuery )
