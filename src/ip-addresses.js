// External dependencies.
import { render } from "@wordpress/element";

// Internal dependencies.
import IpAddresses from "./components/IpAddresses";

( function () {
    const root = document.querySelector( '#ip-addresses-root' );

    if ( root ) {
        const { ipAddresses } = root.dataset;
        const props = { ipAddresses: JSON.parse( ipAddresses ) };
        render( <IpAddresses {...props} />, root );
    }
} )();
