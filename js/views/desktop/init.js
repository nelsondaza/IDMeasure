define(['backbone'], function(){
    console.log(_);
    var View = Backbone.View.extend({
        //el: 'div',
        code: null,
        initialize: function() {
            this.template = [
                '<a class="close" data-dismiss="alert alert-info" href="#">×</a>',
                '<h4 class="alert-heading"><%= title %></h4>',
                '<%= desc  %>'
            ].join("");
            $("#mainInfo").empty().append( this.$el );
            this.$el.addClass( 'alert' );
            this.$el.addClass( 'alert-info' );
        },
        render: function() {
            this.$el.html(_.template( this.template, {title:'Un momento por favor!', desc:'Estoy validando su acceso...'} ) );

            var self = this;
            if( self.options.code )  {
                $.getJSON('/services/session/code/' + self.options.code, function( session )    {
                    console.log( "code answer" );
                    console.log( session );
                    var items = [];
                    if( session.active )    {
                        self.goHome( );
                    }
                    else    {
                        self.$el.empty();
                        self.$el.removeClass( 'alert-info' );
                        self.$el.addClass( 'alert-error' );
                        self.$el.html(_.template( self.template, {
                            title:'Error de Autorización!',
                            desc:"No se permite el acceso a la aplicación, por favor verifique su ingreso."} ) );
                    }
                });
            }
            else    {
                $.getJSON('/services/session/init', function( session )    {
                    console.log( "init answer" );
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
            this.$el.html(_.template( this.template, {title:'Bienvenido!', desc:'Redirigiendo al home...'} ) );
            document.location.href = '/#home';
        },
        'redirect': function( msg, url )  {
            this.$el.html(_.template( this.template, {title:'Un momento por favor!', desc:msg} ) );

            if( !url )
                url = "/#about";

            document.location.href = url;
        }
    });
    return View;
});