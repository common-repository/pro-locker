// External dependencies.
import { useState } from "@wordpress/element";
import { Modal, TextControl, Button } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { addQueryArgs } from "@wordpress/url";
import apiFetch from "@wordpress/api-fetch";

/**
 * BlacklistedAddNewModal component
 *
 * @since 1.0.0
 * @param {object} { handleCloseModal }
 * @returns React element
 */
function BlacklistedAddNewModal( { handleCloseModal } ) {
    const [ ipAddress, setIpAddress ] = useState( '' );
    const [ error, setError ] = useState( '' );
    const [ loading, setLoading ] = useState( false );

    /**
     * Handles the onRequestClose Modal prop.
     *
     * @since 1.0.0
     */
    function handleRequestClose() {
        setIpAddress( '' );
        handleCloseModal();
    }

    /**
     * Adds a new IP address to the blacklist using the REST API.
     *
     * @since 1.0.0
     * @returns
     */
    async function handleAddNew() {
        if ( '' === ipAddress ) {
            return;
        }

        setError( '' );
        setLoading( true );
        const path = 'prolocker/v1/proips';
        const method = 'POST';
        const data = { ip_address: ipAddress };

        try {
            await apiFetch( { path, method, data } );
            const redirectUrl = addQueryArgs( '', { post_type: 'proip', saved: 1 } );
            window.location = redirectUrl;
        } catch ( error ) {
            setLoading( false );
            setError( error.message );
        }
    }

    return (
        <Modal
            title={__( 'Add New IP Address', 'prolocker' )}
            onRequestClose={handleRequestClose}
        >
            <TextControl
                label={__( 'IP Address:', 'prolocker' )}
                help={__( 'Ex: 127.0.0.1', 'prolocker' )}
                value={ipAddress}
                onChange={value => setIpAddress( value.trim() )}
            />
            {error &&
                <p className="error-message">{error}</p>
            }
            <div className="d-flex justify-content-end">
                <Button
                    isSecondary
                    isLarge
                    disabled={loading}
                    onClick={handleRequestClose}
                    className="mr-2"
                >
                    {__( 'Cancel', 'prolocker' )}
                </Button>
                <Button
                    isPrimary
                    isLarge
                    isBusy={loading}
                    disabled={loading}
                    onClick={handleAddNew}
                >
                    {loading ? __( 'Adding...', 'prolocker' ) : __( 'Add', 'prolocker' )}
                </Button>
            </div>
        </Modal>
    );
}

export default BlacklistedAddNewModal;
