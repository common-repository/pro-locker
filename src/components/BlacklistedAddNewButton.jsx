// External dependencies.
import { Fragment, useState } from "@wordpress/element";
import { Button } from "@wordpress/components";
import { __ } from "@wordpress/i18n";

// Internal dependencies.
import BlacklistedAddNewModal from "./BlacklistedAddNewModal";

/**
 * BlacklistedAddNewButton component.
 *
 * @since 1.0.0
 * @returns React element
 */
function BlacklistedAddNewButton() {
    const [ modalOpen, setModalOpen ] = useState( false );

    return (
        <Fragment>
            <Button isSecondary onClick={() => { setModalOpen( true ) }}>{__( 'Add New', 'prolocker' )}</Button>
            {modalOpen && (
                <BlacklistedAddNewModal handleCloseModal={() => { setModalOpen( false ) }} />
            )}
        </Fragment>
    );
}

export default BlacklistedAddNewButton;
