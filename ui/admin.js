// External dependencies.
import ReactDOM from 'react-dom';
import React from 'react';

// Internal dependencies.
import AdminSettings from './components/AdminSettings.js';
import './admin.css';

render();

function render() {
	const container = document.getElementById( 'wp-etsy-listings-container' );

	if ( container === null ) {
		return;
	}

	ReactDOM.render(
		<div className="wrap">
			<AdminSettings />
		</div>,
		container
	);
}
