define(['jquery', 'backbone', 'text!templates/desktop/msg_info.html'], function($, Backbone, template){
    var View = Backbone.View.extend({
        el: 'body',
        code: null,
        initialize: function() {
            this.template = template;
        },
        render: function() {
            this.$el.find("#main").append( this.template );

            var main = this;
            if( main.options.code )  {
                $.getJSON('/services/session/code/' + main.options.code, function( session )    {
                    var items = [];
                    if( session.active )    {
                        main.goHome( );
                    }
                    else if( session.action == 'redirect' )  {
                        main.redirect( 'Autorizaci&oacute;n requerida, enviando ...', session.url );
                    }
                });
            }
            else    {
                $.getJSON('/services/session/init', function( session )    {
                    if( session.active )    {
                        main.goHome( );
                    }
                    else if( session.action == 'redirect' )  {
                        main.redirect( 'Autorizaci&oacute;n requerida, enviando ...', session.url );
                    }
                });
            }
        },
        'goHome': function()  {
            this.$el.find("#main .infoHolder").html( 'El usuario ya está activo, redireccionando al home ...' );
            this.$el.find("#main .infoHolder").fadeOut( 'slow' );
            document.location.href = '/#/home/';
        },
        'redirect': function( msg, url )  {
            this.$el.find("#main .infoHolder").html( msg );
            this.$el.find("#main .infoHolder").fadeOut( 'slow' );

            console.log( url );
            if( confirm( 'Redirigir?' ) )
                document.location.href = url;
        }
    });
    return View;
});