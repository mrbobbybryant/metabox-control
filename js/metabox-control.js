/**
 * Created by Bobby on 1/5/16.
 */
var metaboxControl = (function( window ) {
    var pageTemplateDropdown = document.getElementById('page_template');
    var registeredTemplates = [];


    //======================================================================
    // STATE MANAGEMENT
    //======================================================================

    var setInitialState = function( initialStateData ) {
        /**
         * Check to see if we got any Templates from the WordPress Database.
         */
        if ( initialStateData ) {
            return setState( { event: 'INITIAL_STATE', initialObject : initialStateData.data } );
        }

        return false;
    };

    var setState = function( stateRequest ) {
        /**
         * Typecheck Input
         */
        is_object( stateRequest );

        switch( stateRequest.event ) {

            case 'ADD_TEMPLATE':
                updateState( addObject( stateRequest.newObject ) , checkState, updateTemplateEvents );
                break;
            case 'REMOVE_TEMPLATE':
                updateState( removeObject( stateRequest.template ), checkState, updateTemplateEvents );
                break;
            case 'INITIAL_STATE':
                updateState( stateRequest.initialObject, checkState, updateTemplateEvents );
        }
    };

    var checkState = function( newState ) {
        /**
         * Typecheck Input
         */
        is_object( newState );

        return _.isEqual( newState, registeredTemplates );
    };

    var updateState = function( newState, callback, update ) {

        /**
         * Typechecking Inputs
         */
        is_array( newState );
        is_func( callback );

        var checkState = callback( newState );

        if ( ! checkState ) {
            registeredTemplates = newState;
            update();
        }
    };

    //======================================================================
    // INTERNAL API METHODS
    //======================================================================
    var addObject = function( template ) {
        return registeredTemplates.concat( template );
    };

    var removeObject = function( template ) {
        return registeredTemplates.filter( function( data ){
            return data.template !== template;
        });
    };

    //======================================================================
    // UTILITY METHODS
    //======================================================================

    var maybeEmpty = function( $error, $value ) {
        if ( $value.length === 0 ) {
            throw $error;
        } else {
            return $value;
        }
    };

    var getWPTemplates = function( dropdown ) {
        var templates = [];

        Array.prototype.forEach.call( dropdown, function( el, i ){
            templates.push( el.value );
        });

        return templates;
    };

    var getTemplateNames = function( collection ) {
        return collection.map( function( data ){
            return data.template;
        });
    };

    var pageTemplateExists = function( collection, item ) {
        var template = collection.filter( function( data ){
            if ( data === item ) {
                return data;
            }
        });

        return maybeEmpty( 'Template does not exist', template );

    };

    var getElementsById = function( id ) {
        return [ document.getElementById( id ) ];
    };

    var fetchWPTemplate = function() {
        var URL = 'http://sandbox.dev/' + ajaxurl + '?action=get_registered_templates&security=' + mbControl.security;
        var request = new XMLHttpRequest();
        request.open('GET', URL, true);

        request.onload = function() {
            if ( request.status >= 200 && request.status < 400 ) {
                var response = request.responseText;
                setInitialState( JSON.parse(response) );
            } else {
                console.log( 'Unable to complete request ' + request.status );
            }
        };

        request.onerror = function() {
            console.log( 'failed to fetch existing template fromt he database.' );
        };

        request.send();
    };
    var updateTemplateEvents = function() {
        register_metabox_event( window, 'load', getTemplates );
        register_metabox_event( pageTemplateDropdown, 'change', getTemplates );
    };

    //======================================================================
    // TYPECHECKING UTILITY METHODS
    //======================================================================
    var typeOf = function( type ) {
        return function( value ) {
            if ( typeof value !== type ) {
                throw new TypeError( 'Expected a ' + type + '!' );
            } else {
                return value;
            }
        };
    };

    var is_array = function(arr) {
        if ( {}.toString.call(arr) !== '[object Array]' ) {
            throw new TypeError( 'Expected an Array!' );
        } else {
            return arr;
        }
    };

    var is_func = typeOf( 'function' );
    var is_bool = typeOf( 'boolean' );
    var is_string = typeOf( 'string' );
    var is_undef = typeOf( 'undefined' );
    var is_object = typeOf( 'object' );

    //======================================================================
    // WINDOW IO METHODS
    //======================================================================

    var renderMetabox = function( metaboxes ) {
        metaboxes = getElementsById( metaboxes );
        metaboxes.forEach( function( metabox ) {
            metabox.style.display = '';
        });
    };

    var hideMetabox = function( metaboxes ) {
        metaboxes = getElementsById( metaboxes );
        metaboxes.forEach( function( metabox ) {
            metabox.style.display = 'none';
        });
    };

    var computeVisibility = function( template, currentTemplate ) {
        if ( template.template === currentTemplate ) {
            renderMetabox( template.metaboxes );
        } else {
            hideMetabox( template.metaboxes );
        }
    };

    var register_metabox_event = function( el, event, collection ) {
        /**
         * Typecheck Inputs
         */
        is_object( el );
        is_string( event );
        is_func( collection );

        var templates = collection();

        el.addEventListener( event, function() {
            templates.forEach( function( data ){
                computeVisibility( data, pageTemplateDropdown.value );
            });
        });
    };

    //======================================================================
    // EXTERNAL API METHODS
    //======================================================================

    var addTemplate = function( templateName, metaboxes ) {
        try {

            /**
             * Typecheck Inputs
             */
            is_string( templateName );
            is_array( metaboxes );

            /**
             * Checking to see if the template the user provided exists in WordPress.
             */
            var WPTemplates = getWPTemplates( pageTemplateDropdown );
            pageTemplateExists( WPTemplates, templateName );

            setState( { event: 'ADD_TEMPLATE', newObject : { template: templateName, metaboxes: metaboxes } } );

        } catch( error ) {
            console.log( "Error: " + error );
        }

        return true;
    };

    var removeTemplate = function( templateName ) {
        try {

            /**
             * TypeCheck Template
             */
            is_string( templateName );

            /**
             * Check to see if that template as been registered with Metabox Control.
             */
            var templateNames = getTemplateNames( registeredTemplates );
            pageTemplateExists( templateNames, templateName );

            setState( { event : 'REMOVE_TEMPLATE', template: templateName } );

        } catch( error) {
            console.log( "Error: " + error );
        }

        return true
    };

    var getTemplates = function() {
        return registeredTemplates;
    };

    var getCurrentTemplateValue = function() {
        return pageTemplateDropdown.value;
    };

    var init = function() {
        fetchWPTemplate();
    };

    //======================================================================
    // PUBLIC OUTPUT
    //======================================================================
    return {
        getTemplates: getTemplates,
        currentTemplate: getCurrentTemplateValue,
        addTemplate : addTemplate,
        removeTemplate : removeTemplate,
        initialize: init
    };

}(window));

document.addEventListener('DOMcontentLoaded', metaboxControl.initialize());

// TODO
//get list of default WordPress metaboxes
//create external API method to hide default metaboxes