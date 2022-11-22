var exportHumans = {
	ready: function( fn ) {
		if ( document.readyState != 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	},

	addEvents: function( fn ) {
		var table = document.querySelector( '.lh_bexd tbody' ),
		button    = document.querySelector( '.page-title-action' );

		button.addEventListener( 'click', exportHumans.addRow );
		table.addEventListener( 'change', exportHumans.handleSelectBox );
		table.addEventListener( 'click', exportHumans.deleteRow );
	},

	addRow: function( e ) {
		e.preventDefault();

		var template    = wp.template( 'lh_bexd-row' ),
		templateElement = document.createElement( 'template' );

		templateElement.innerHTML = template( {} );
		document.querySelector( 'table.lh_bexd tbody' ).appendChild( templateElement.content );
	},

	handleSelectBox: function( e ) {
		if ( e.target.tagName != 'SELECT' ) {
			return;
		}

		if ( ! e.target.getAttribute( 'previous-value' ) ) {
			var lastRow = document.querySelector( '#lh_bexd-table tbody tr:last-child select' );

			// If the last selectbox is empty, don't add another row.
			if ( lastRow.options[ lastRow.selectedIndex ].value ) {
				exportHumans.addRow( e );
			}
		}

		e.target.setAttribute( 'previous-value', 1 );
	},

	deleteRow: function( e ) {
		e.preventDefault();
		
		var row;

		if ( e.target.classList.contains( 'action-column' ) ) {
			row = e.target.parentElement;
		} else if ( e.target.classList.contains( 'lh_bexd-delete-icon' ) ) {
			row = e.target.parentElement.parentElement;
		} else {
			return;
		}

		row.remove();
	}
};

exportHumans.ready( exportHumans.addEvents );