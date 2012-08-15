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
            $("#main").empty().append( this.$el );
            this.$el.addClass( 'alert' );
            this.$el.addClass( 'alert-info' );
        },
        render: function() {
            this.$el.html(_.template( this.template, {title:'Un momento por favor!', desc:'Estoy validando su acceso...'} ) );

            var main = this;
            if( main.options.code )  {
                $.getJSON('/services/session/code/' + main.options.code, function( session )    {
                    console.log( "code answer" );
                    console.log( session );
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
                    console.log( "init answer" );
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
            this.$el.html(_.template( this.template, {title:'Bienvenido!', desc:'Redirigiendo al home...'} ) );
            document.location.href = '/#home';
        },
        'redirect': function( msg, url )  {
            this.$el.html(_.template( this.template, {title:'Un momento por favor!', desc:msg} ) );

            console.log( url );
            if( confirm( '¿Permitir redirigir?' ) )
                document.location.href = url;
        }
    });
    return View;
});