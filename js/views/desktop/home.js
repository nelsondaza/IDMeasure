define(['backbone', 'text!templates/desktop/home.html'], function(Backbone, template){
    var View = Backbone.View.extend({
        initialize: function() {
            mainRouter.hideMainMessage();
            var main = $('#main');
            main.empty();
            main.append( this.$el );

            //this.template = _.template( template, { } );
            this.template = template;
            var self = this;

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
            //"click #main": "promptUser"
        },
        render: function() {
            this.$el.append( this.template );
        },
        promptUser: function() {
            prompt("Isn't this amazing?", "Yes, ...");
        }
    });
    // Returns the View class
    return View;
});