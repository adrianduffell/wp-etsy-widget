import React, { useState, useEffect } from 'react';
import {
	Button,
	TextControl,
	Panel,
	PanelBody,
	PanelRow,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

function AdminSettings() {
	const [ shop, setShop ] = useState( null );
	const [ pending, setPending ] = useState( true );
	const [ saving, setSaving ] = useState( false );

	const save = ( data ) => {
		setSaving( true );
		apiFetch( {
			path: '/wp/v2/settings',
			method: 'POST',
			data,
		} ).then( ( response ) => {
			setShop( response.wp_etsy_listings_shop );
			setSaving( false );
		} );
	};

	// Fetch settings on initial render.
	useEffect( () => {
		apiFetch( { path: '/wp/v2/settings' } ).then( ( settings ) => {
			setShop( settings.wp_etsy_listings_shop );
			setPending( false );
		} );
	}, [] );

	// Don't render if data is pending.
	if ( pending ) {
		return null;
	}

	return (
		<>
			<h1>{ __( 'WP Etsy Listings Settings' ) }</h1>

			<Panel>
				<PanelBody>
					<PanelRow>
						<TextControl
							label={ __( 'Shop Name' ) }
							value={ shop }
							onChange={ ( value ) => setShop( value ) }
						/>
					</PanelRow>
					<PanelRow>
						<Button
							isPrimary
							isLarge
							disabled={ saving }
							onClick={ () =>
								save( {
									wp_etsy_listings_shop: shop,
								} )
							}
						>
							Save Changes
						</Button>
					</PanelRow>
				</PanelBody>
			</Panel>
		</>
	);
}

export default AdminSettings;
