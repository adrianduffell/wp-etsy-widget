// External dependencies.
import React from 'react';
import { act } from 'react-dom/test-utils';
import { render, cleanup, fireEvent, screen } from '@testing-library/react';
import '@testing-library/jest-dom/extend-expect';
import apiFetch from '@wordpress/api-fetch';

// Internal dependencies.
import AdminSettings from '../components/AdminSettings.js';

// Mocks.
jest.mock( '@wordpress/api-fetch', () => jest.fn() );

beforeEach( async () => {
	apiFetch.mockImplementation( () => {
		// Settings retrieved. Return a normal value.
		return Promise.resolve( { wp_etsy_listings_shop: 'foo' } );
	} );

	// Initial render.
	await act( async () => {
		render( <AdminSettings /> );
	} );
} );

afterEach( cleanup );

describe( '<AdminSettings />', () => {
	it( 'should render', async () => {
		// Heading.
		expect( screen.getByRole( 'heading' ) ).toHaveTextContent(
			'WP Etsy Listings Settings'
		);

		// Initial input value should match the API get request value.
		expect( screen.getByLabelText( 'Shop Name' ).value ).toBe( 'foo' );
		expect( screen.getByRole( 'button' ) ).toBeVisible();
	} );

	it( 'should allow shop name input to be changed', async () => {
		// Change the input value directly.
		await act( async () => {
			const input = screen.getByLabelText( 'Shop Name' );
			fireEvent.change( input, { target: { value: 'bar' } } );
		} );

		// Value should have changed.
		expect( screen.getByLabelText( 'Shop Name' ).value ).toBe( 'bar' );
	} );

	it( 'should save', async () => {
		// Settings saved. Return a sanitized value.
		apiFetch.mockImplementation( () => {
			return Promise.resolve( {
				wp_etsy_listings_shop: 'sanitized_bar',
			} );
		} );

		await act( async () => {
			fireEvent.click( screen.getByRole( 'button' ) );
		} );

		// Value should be a sanitized value returned from the API.
		expect( screen.getByLabelText( 'Shop Name' ).value ).toBe(
			'sanitized_bar'
		);
	} );
} );
