// External dependencies.
import { render } from "@wordpress/element";

// Internal dependencies.
import BlacklistedAddNewButton from "./components/BlacklistedAddNewButton";

( function () {
    const heading = document.querySelector( '.wrap h1.wp-heading-inline' );
    const spanRoot = document.createElement( 'span' );
    spanRoot.classList.add( 'prolocker-custom-add-new-container' );
    heading.after( spanRoot );

    render( <BlacklistedAddNewButton />, spanRoot );
} )();
