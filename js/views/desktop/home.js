define(['jquery', 'backbone', 'text!templates/desktop/home.html'], function($, Backbone, template){
    var View = Backbone.View.extend({
        // Represents the actual DOM element that corresponds to your View
        // (There is a one to one relationship between View Objects and DOM elements)
        el: 'body',
        initialize: function() {
            // Setting the view's model property to the passed in model
            //this.model = new Model();
            // Setting the view's template property
            //this.template = _.template( template, { } );
            //this.template = template;
            //var main = this;

            console.log( 'home!!');
/*
            $.getJSON('/services/session/init', function( session )    {
                var items = [];
                console.log( 'Acción' );
                console.log( session );

                if( session.active )    {
                    main.$el.find("#main .infoHolder").html( 'El usuario ya está activo, redireccionando al home ...' );
                    main.$el.find("#main .infoHolder").fadeOut( 'slow' );
                }
                else    {
                    if( session.action == 'redirect' )  {
                        main.$el.find("#main .infoHolder").html( 'Autorizaci&oacute;n requerida, redireccionando ...' );
                        document.location.href = session.url;
                    }

                }


            });
            */
            /*
             $.each(data, function(key, val) {
             items.push('<li id="' + key + '">' + val + '</li>');
             });

             $('<ul/>', {
             'class': 'my-new-list',
             html: items.join('')
             }).appendTo('body');
             */

        },
        events: {
            "click #main": "promptUser"
        },
        render: function() {
            this.$el.find("#main").append( this.template );
        },
        promptUser: function() {
            prompt("Isn't this amazing?", "Yes, ...");
        }
    });
    // Returns the View class
    return View;
});