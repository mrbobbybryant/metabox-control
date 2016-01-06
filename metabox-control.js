/**
 * Created by Bobby on 1/5/16.
 */
var metaboxControl = (function() {
    var pageTemplateDropdown = document.getElementById('page_template');
    var registeredTemplates = [];


    //======================================================================
    // STATE MANAGEMENT
    //======================================================================

    var checkState = function( newState ) {
        return _.isEqual( newState, registeredTemplates );
    };

    var setState = function( newState, callback ) {
        var update = callback( newState );

        switch( newState.event ) {
            case 'ADD_TEMPLATE':
                if ( ! update ) {
                    return ( registeredTemplates = newState.newState );
                }
                break;
            case 'REMOVE_TEMPLATE':
                if ( ! update ) {
                    return ( registeredTemplates = newState.newState );
                }
        }
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
        return collection.map(function( data ){
            return data.template;
        });
    };

    var pageTemplateExists = function( collection, item ) {
        var template = collection.filter(function(data){
            if ( data === item ) {
                return data;
            }
        });

        return maybeEmpty( 'Template does not exist', template );

    };

    var getElementsById = function( id ) {
        return [ document.getElementById( id ) ];
    };

    //======================================================================
    // WINDOW IO METHODS
    //======================================================================

    var renderMetabox = function( metaboxes ) {
        metaboxes.forEach( function( metabox ) {
            metabox.style.display = '';
        });
    };

    var hideMetabox = function( metaboxes ) {
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

    //======================================================================
    // EXTERNAL API METHODS
    //======================================================================

    var addTemplate = function( templateName, metaboxes ) {
        try {

            var WPTemplates = getWPTemplates( pageTemplateDropdown );
            pageTemplateExists( WPTemplates, templateName );

            var metaboxDOMElements = getElementsById( metaboxes );
            var newState = registeredTemplates.concat({ template: templateName, metaboxes: metaboxDOMElements });

        } catch( error ) {
            console.log( "Error: " + error );
        }

        return setState( { event : 'ADD_TEMPLATE', newState: newState }, checkState );
    };

    var removeTemplate = function( templateName ) {
        try {
            var templateNames = getTemplateNames( registeredTemplates );
            pageTemplateExists( templateNames, templateName );

            var newState = registeredTemplates.filter( function( data ){
                return data.template !== templateName;
            });

        } catch( error) {
            console.log( "Error: " + error );
        }

        return setState( { event : 'REMOVE_TEMPLATE', newState: newState }, checkState );
    };

    var getTemplates = function() {
        return registeredTemplates;
    };

    var getCurrentTemplateValue = function() {
        return pageTemplateDropdown.value;
    };

    var init = function() {
        var templates = getTemplates();

        window.addEventListener( 'load', function() {
            templates.forEach( function( data ){
                computeVisibility( data, pageTemplateDropdown.value );
            });
        });

        pageTemplateDropdown.addEventListener( 'change', function() {
            templates.forEach( function( data ){
                computeVisibility( data, pageTemplateDropdown.value );
            });
        });
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

}());

console.log(metaboxControl.getTemplates());
metaboxControl.addTemplate( 'page-one.php', ['mb_one']);
console.log(metaboxControl.getTemplates());
metaboxControl.addTemplate( 'page-two.php', ['mb_two']);
console.log(metaboxControl.getTemplates());
metaboxControl.initialize();

// TODO
//get list of default WordPress metaboxes
//create external API method to hide default metaboxes