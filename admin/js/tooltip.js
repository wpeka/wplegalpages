/**
 * Tooltip JavaScript.
 *
 * @package    Wplegalpages
 * @subpackage Wplegalpages/admin
 * @author     wpeka <https://club.wpeka.com>
 */

jQuery( document ).ready(
	function(){
		// jQuery( '[data-toggle="tooltip"]' ).tooltip();.

		jQuery( '#lp-is_adult' ).change(
			function(){
				if (this.checked) {
					jQuery( '#exit_url_section' ).show();
				} else {
					jQuery( '#exit_url_section' ).hide();
				}
			}
		);

		jQuery( '.wplegal-template-language' ).change(
			function() {
				var selectedLang = jQuery( this ). children( "option:selected" ). val();
				if (selectedLang == 'eng') {
					jQuery( '.wplegal-template-eng' ).css( {"display":"list-item"} );
					jQuery( '.wplegal-template-fr' ).css( {"display":"none"} );
					jQuery( '.wplegal-template-de' ).css( {"display":"none"} );
				}
				if (selectedLang == 'fr') {
					jQuery( '.wplegal-template-fr' ).css( {"display":"list-item"} );
					jQuery( '.wplegal-template-eng' ).css( {"display":"none"} );
					jQuery( '.wplegal-template-de' ).css( {"display":"none"} );
				}
				if (selectedLang == 'de') {
					jQuery( '.wplegal-template-de' ).css( {"display":"list-item"} );
					jQuery( '.wplegal-template-fr' ).css( {"display":"none"} );
					jQuery( '.wplegal-template-eng' ).css( {"display":"none"} );
				}
			}
		);

	}
);
