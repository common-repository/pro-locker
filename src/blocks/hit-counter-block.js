// External dependencies.
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

// Internal dependencies.
import HitCounterEdit from "./components/HitCounterEdit";
import HitCounterSave from "./components/HitCounterSave";

// Hit counter block registration.
registerBlockType( 'prolocker/hit-counter', {
    title: __( 'Hit counter', 'prolocker' ),
    category: 'prolocker',
    icon: 'editor-unlink',
    edit( props ) {
        return <HitCounterEdit {...props} />;
    },
    save() {
        return <HitCounterSave />
    }
} );
