define(['backbone'], function(){
    var initView = Backbone.View.extend({
        code: null,
        initialize: function() {

        },
        render: function() {
            mainRouter.showMainMessage( 'info', 'Un momento por favor!', 'Estoy validando su acceso...' );

            var self = this;
            if( self.options.code )  {
                $.getJSON('/services/session/code/' + self.options.code, function( session )    {
                    var items = [];
                    if( session.active )    {
                        self.goHome( );
                    }
                    else    {
                        mainRouter.showMainMessage( 'error', 'Error de Autorización!', 'No se permite el acceso a la aplicación, por favor verifique su ingreso.' );
                    }
                });
            }
            else    {
                $.getJSON('/services/session/init', function( session )    {
                    if( session.active )    {
                        self.goHome( );
                    }
                    else if( session.action == 'redirect' )  {
                        self.redirect( 'Autorizaci&oacute;n requerida, enviando ...', session.url );
                    }
                });
            }
        },
        'goHome': function()  {
            mainRouter.showMainMessage( 'info', 'Bienvenido!', 'Redirigiendo al home...' );
            mainRouter.goHome();
        },
        'redirect': function( msg, url )  {
            mainRouter.showMainMessage( 'info', 'Un momento por favor!', msg );
            mainRouter.navigateToURL( url );
        }
    });
    return initView;
});