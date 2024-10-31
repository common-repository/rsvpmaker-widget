/**
 * BLOCK: rsvpmaker-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { TextControl } = wp.components;
const rsvpupcoming = [];

registerBlockType( 'rsvpmaker/upcoming-by-json', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'RSVPMaker Events (fetch via API)' ), // Block title.
	icon: 'calendar-alt', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	description: __('Displays a listing of RSVPMaker events from a remote site'),
	keywords: [
		__( 'RSVPMaker' ),
		__( 'Events' ),
		__( 'Calendar' ),
	],
       attributes: {
            limit: {
                type: 'int',
				default: 10,
            },
            url: {
                type: 'string',
                default: '',
            },
            morelink: {
                type: 'string',
                default: '',
            },
        },
	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: function( props ) {
	const { attributes: { limit, url, morelink }, setAttributes, isSelected } = props;
	let typelist = '';
	if(rsvpupcoming && (rsvpupcoming.length > 2))
	{
		typelist = 'API urls for  this site:\n'+window.location.protocol+'//'+window.location.hostname+'/wp-json/rsvpmaker/v1/future\n';
		rsvptypes.forEach(showTypes);	
	}

function showTypes (data, index) {
	if(index > 0)
		typelist = typelist.concat(rsvpmaker_json_url+'type/'+data.value + '\n'); 
}

function showForm() {
return (<div>
	<TextControl
        label={ __( 'JSON API url', 'rsvpmaker' ) }
        value={ url }
        onChange={ ( url ) => setAttributes( { url } ) }
    />
	<TextControl
        label={ __( 'Limit', 'rsvpmaker' ) }
        value={ limit }
		help={__('For no limit, enter 0')}
        onChange={ ( limit ) => setAttributes( { limit } ) }
    />	
	<TextControl
        label={ __( 'Link URL for more results (optional)', 'rsvpmaker' ) }
        value={ morelink }
        onChange={ ( morelink ) => setAttributes( { morelink } ) }
    />	
	<p><em>Enter JSON API url for this site or another in the format:
	<br />https://rsvpmaker.com/wp-json/rsvpmaker/v1/future
	<br />or
	<br />https://rsvpmaker.com/wp-json/rsvpmaker/v1/type/featured</em></p>
<pre>{typelist}</pre>
</div>);
}

function showFormPrompt () {
    return (<p><em>Click to set options</em></p>);
}

		return (
			<div className={ props.className }>
				<p  class="dashicons-before dashicons-calendar-alt"><strong>RSVPMaker </strong>: Add an Events Listing that dynamically loads via JSON API endpoint
				</p>
			{ isSelected && ( showForm() ) }
			{ !isSelected && ( showFormPrompt() ) }
			</div>
		);
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function( props ) {
		return null;
	},
} );
