// External dependencies.
import { useState } from "@wordpress/element";
import { TextControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

// Internal dependencies.
import IpAddress from "./IpAddress";

/**
 * IpAddresses component.
 *
 * @since 1.0.0
 * @param {object} props
 * @returns React element
 */
function IpAddresses( props ) {
    const [ ipAddresses, setIpAdresses ] = useState( props.ipAddresses );

    /**
     * Handles the change event for the search IP text input.
     *
     * @since 1.0.0
     * @param {string} value
     * @returns {array} Filtered list of IP addresses.
     */
    function handleSearchChange( value ) {
        const { ipAddresses } = props;

        if ( '' === value ) {
            setIpAdresses( ipAddresses );
            return;
        }

        const filteredIpAddresses = ipAddresses.filter( ipAddress => {
            if ( -1 !== ipAddress.post_title.indexOf( value ) ) {
                return ipAddress;
            }
        } );
        setIpAdresses( filteredIpAddresses );
    }

    return (
        <div>
            <p>
                <TextControl
                    label={__( 'Search IP address:', 'prolocker' )}
                    onChange={handleSearchChange}
                />
            </p>
            <ul className="prokey-ip-addresses-list">
                {ipAddresses.map( ipAddress => (
                    <IpAddress key={ipAddress.ID} ipAddress={ipAddress.post_title} status={ipAddress.post_status} />
                ) )}
            </ul>
        </div>
    );
}

export default IpAddresses;
