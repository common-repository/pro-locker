// External dependencies.
import { InnerBlocks, InspectorControls } from "@wordpress/editor";
import { BaseControl, PanelBody, TextControl, TextareaControl, Button } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { useState, Fragment } from "@wordpress/element";

/**
 * HitCounterEdit component.
 *
 * @since 1.0.0
 * @param {object} props
 * @returns React element
 */
function HitCounterEdit( props ) {
    const { attributes: { count, message }, setAttributes, className, isSelected } = props;
    const { theme } = window.prolocker;
    const [ showPreview, setShowPreview ] = useState( false );
    let lockedMessageClassname = '';

    switch ( theme ) {
        case 'Twenty Nineteen':
            lockedMessageClassname += ' prolocker-locking-block--twenty-nineteen';
            break;
        case 'Twenty Twenty':
            lockedMessageClassname += ' prolocker-locking-block--twenty-twenty';
            break;
        case 'Go First':
            lockedMessageClassname += ' prolocker-locking-block--go-first';
            break;
    }

    /**
     * Sets the value for the count attribute.
     *
     * @since 1.0.0
     * @param number count
     */
    function setCount( count ) {
        count = parseInt( count );
        setAttributes( { count } );
    }

    /**
     * Sets the value for the message attribute.
     *
     * @since 1.0.0
     * @param string message
     */
    function setMessage( message ) {
        setAttributes( { message } );
    }

    return (
        <div className={className}>
            {isSelected &&
                <InspectorControls>
                    <PanelBody
                        title={__( 'Configuration', 'prolocker' )}
                        initialOpen={true}
                    >
                        <BaseControl id="count">
                            <TextControl
                                label={__( 'Count', 'prolocker' )}
                                help={__( 'Number of times the URL has to be shared to unlock content.', 'prolocker' )}
                                type="number"
                                value={count}
                                min="1"
                                onChange={setCount}
                            />
                        </BaseControl>
                        <BaseControl id="message">
                            <TextareaControl
                                label={__( 'Message', 'prolocker' )}
                                help={__( 'Message to be shown to the user. You can use the placeholder {count} anywhere in the message. The placeholder will be replaced with the actual count needed to unlock the content.', 'prolocker' )}
                                value={message}
                                onChange={setMessage}
                            />
                        </BaseControl>
                    </PanelBody>
                </InspectorControls>
            }
            <p className="wp-block-prolocker-hit-counter-locking-options-text">
                {__( 'Click here to configure locking options', 'prolocker' )}
            </p>
            {!showPreview &&
                <Button
                    isLink
                    onClick={() => setShowPreview( true )}
                >
                    {__( 'Show locked message preview', 'prolocker' )}
                </Button>
            }
            {showPreview &&
                <Fragment>
                    <p className="wp-block-prolocker-hit-counter-preview-text">
                        <small>{__( 'Locked message preview:', 'prolocker' )}</small>
                        &nbsp;&nbsp;
                        <Button
                            isLink
                            onClick={() => setShowPreview( false )}
                        >
                            {__( 'Hide', 'prolocker' )}
                        </Button>
                    </p>
                    <p className={`prolocker-locking-block${lockedMessageClassname}`}>
                        {message.replace( '{count}', count )}
                        <br /><br />
                        {__( 'https://example.com/post-title?pk=abc123456', 'prolocker' )}
                    </p>
                </Fragment>
            }
            <InnerBlocks templateLock={false} />
        </div>
    );
}

export default HitCounterEdit;
