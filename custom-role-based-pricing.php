<?php

/**
 * Plugin Name: Role-Based Pricing for WooCommerce
 * Plugin URI: https://yourwebsite.com
 * Description: Change WooCommerce product prices based on user roles and display special prices to specific users.
 ** Version: 1.0
 * * Author: milad jafari gavzan
 * * Author URI: https://miladjafarigavzan.ir
 * * License: GPL-2.0+
 */

// Hook to modify product price based on user role
add_filter( 'woocommerce_product_get_price', 'role_based_price', 10, 2 );
add_filter( 'woocommerce_product_get_regular_price', 'role_based_price', 10, 2 );

function role_based_price( $price, $product ) {
	if ( is_user_logged_in() ) {
		// Get current user data
		$user = wp_get_current_user();

		// Check the role of the logged-in user
		if ( in_array( 'wholesale_customer', (array) $user->roles ) ) {
			// Apply a discount or special price for wholesale customers
			$price = $price * 0.8; // 20% discount
		} elseif ( in_array( 'vip_customer', (array) $user->roles ) ) {
			// Apply a different discount for VIP customers
			$price = $price * 0.9; // 10% discount
		}
	}

	return $price;
}

// Show original price with a strike-through for discounted users
add_filter( 'woocommerce_get_price_html', 'role_based_price_html', 100, 2 );

function role_based_price_html( $price_html, $product ) {
	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();

		// Apply the same logic as above for role-based display
		if ( in_array( 'wholesale_customer', (array) $user->roles ) ) {
			$regular_price    = wc_price( $product->get_regular_price() );
			$discounted_price = wc_price( $product->get_price() );
			$price_html       = '<del>' . $regular_price . '</del> <ins>' . $discounted_price . '</ins>';
		} elseif ( in_array( 'vip_customer', (array) $user->roles ) ) {
			$regular_price    = wc_price( $product->get_regular_price() );
			$discounted_price = wc_price( $product->get_price() );
			$price_html       = '<del>' . $regular_price . '</del> <ins>' . $discounted_price . '</ins>';
		}
	}

	return $price_html;
}

