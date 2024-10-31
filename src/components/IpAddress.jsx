// External dependencies.
import { __ } from "@wordpress/i18n";
import { Fragment, useState } from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";
import { Modal, Button } from "@wordpress/components";

/**
 * IpAddress component.
 *
 * @since 1.0.0
 * @param {object} props
 * @returns React element
 */
function IpAddress( props ) {
    const { ipAddress } = props;
    const [ status, setStatus ] = useState( props.status );
    const [ adding, setAdding ] = useState( false );
    const [ error, setError ] = useState( '' );

    /**
     * Adds a new IP address to the blacklist using the REST API.
     *
     * @since 1.0.0
     * @param {object} e
     */
    async function handleAddToTheBlacklist( e ) {
        e.preventDefault();
        setError( '' );
        setAdding( true );
        const path = 'prolocker/v1/proips';
        const method = 'POST';
        const data = { ip_address: ipAddress };

        try {
            await apiFetch( { path, method, data } );
            setStatus( 'blacklisted' );
            setAdding( false );
        } catch ( error ) {
            setAdding( false );
            setError( error );
        }
    }

    return (
        <Fragment>
            <li className={adding ? 'prokey-ip-addresses-list-item prokey-ip-addresses-list-item--adding' : 'prokey-ip-addresses-list-item'}>
                {'blacklisted' === status ?
                    <span><s>{ipAddress}</s></span> :
                    <span>{ipAddress}</span>
                }
                <br />
                {'blacklisted' === status ?
                    <span>{__( 'Blacklisted', 'prolocker' )}</span> :
                    <span className="prokey-ip-addresses-list-item__add">
                        {adding ?
                            <span>{__( 'Adding...', 'prolocker' )}</span> :
                            <a href="#" role="button" onClick={handleAddToTheBlacklist}>{__( 'Add to the blacklist', 'prolocker' )}</a>
                        }
                    </span>
                }
            </li>
            {error &&
                <Modal
                    title={__( 'ProLocker', 'prolocker' )}
                    onRequestClose={() => { setError( '' ) }}
                >
                    <p className="error-message">{error.message}</p>
                    <div className="d-flex justify-content-end">
                        <Button
                            isPrimary
                            isLarge
                            onClick={() => { setError( '' ) }}
                        >
                            {__( 'OK', 'prolocker' )}
                        </Button>
                    </div>
                </Modal>
            }
        </Fragment>
    );
}

export default IpAddress;
