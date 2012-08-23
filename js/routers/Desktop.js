define(['backbone','text!templates/desktop/about.html'], function(Backbone, aboutText ){
    var Desktop = Backbone.Router.extend({
        initialize: function(){

            if( !window.mainRouter )
                window.mainRouter = this;

            this.routeList = {};
            for( var sIndex in this.routes )    {
                if( sIndex && sIndex[0] != '_' )
                    this.routeList[sIndex] = '#' + this.routes[sIndex].replace( /section/, '').toLowerCase( );
            };

            this.mainInfoTemplate = [
                '<div class="alert alert-<%= type %>">',
                '<a class="close" data-dismiss="alert alert-<%= type %>" href="#">×</a>',
                '<b class="alert-heading"><%= title %></b> ',
                '<%= desc %>',
                '</div>'
            ].join("");

            this.sectionAbout();

            // Tells Backbone to start watching for hashchange events
            Backbone.history.start();

        },
        // All of your Backbone Routes (add more)
        routes: {
            // When there is no hash bang on the url, the init method is called
            '': 'init',
            '_=_': 'redirect',
            'home': 'sectionHome',
            'about': 'sectionAbout'
        },
        'redirect': function(){
            console.log( "redirect called" );
            var self = this;
            require(['views/desktop/init'], function( InitView ) {
                console.log( "redirect loaded" );
                var initView = new InitView( { code:self.getRequest('code') } );
                initView.render();
            });
        },
        'init': function()  {
            console.log( "init called" );
            require(['views/desktop/init'], function( InitView ) {
                console.log( "init loaded" );
                var initView = new InitView();
                initView.render();
            });
         },
        'getRequest':function( name ){
            return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
        },
        'getRoute': function( name )    {
            return this.routeList[name];
        },
        'goHome': function()  {
            console.log( "go Home: " );
            document.location.href = '/#home';
        },
        'navigateTo': function( name )  {
            var url = this.routeList[name];
            if( !url )
                url = "/#about";

            console.log( "navigate To: " + url );
            document.location.href = url;
        },
        'navigateToURL': function( url )  {
            console.log( "navigate URL: " + url );
            if( !url )
                url = "/#about";

            document.location.href = url;
        },
        'hideMainMessage': function( ){
            $("#mainInfo").parent().addClass('hidden');
        },
        'showMainMessage': function( type, title, desc ) {
            if( type && title ) {
                $("#mainInfo").parent().removeClass('hidden');
                $("#mainInfo").empty().html(_.template( this.mainInfoTemplate, { type:type, title:title, desc:desc } ) );
            }
            else
                this.hideMainMessage( );
        },
        'sectionHome': function(){
            console.log( "home called" );
            require(['views/desktop/home'], function( HomeView ) {
                console.log( "home loaded" );
                var homeView = new HomeView();
                homeView.render();
            });
        },
        'sectionAbout': function(){
            var REXLetter = /^[a-z ]{3,}$/i;
            var REXEmail = /^([-\w\.]{2,})@([\w-]+)\.([\w-]{2,4})$/i;
            var REXAny = /^(.{10,})$/;

            console.log( "about called" );
            $('#main').html( aboutText );
            $('#navbar').scrollspy( );

            $('a[href="#about_contact"]').click( function()   {
                setTimeout(function() {$('#inputName').focus();}, 300 );
            });

            $('#inputName').bind( 'blur keyup', function(){
                $(this).closest('.control-group').removeClass('success error').addClass( ( REXLetter.test( $(this).val() ) ? 'success' : 'error' ) );
            });

            $('#inputEmail').bind( 'blur keyup', function(){
                $(this).closest('.control-group').removeClass('success error').addClass( ( REXEmail.test( $(this).val() ) ? 'success' : 'error' ) );
            });

            $('#inputComment').bind( 'blur keyup', function(){
                $(this).closest('.control-group').removeClass('success error').addClass( ( REXAny.test( $(this).val() ) ? 'success' : 'error' ) );
            });

            $('#aboutClear').click(function(){
                var button = $(this);
                button.addClass('disabled');
                button.attr('disabled','disabled');
                button.addClass('btn-warning');
                $('#aboutInfo').removeClass('success error');

                $('#aboutForm').get(0).reset();
                $('#aboutForm .control-group').removeClass('success error');

                setTimeout( function(){
                    button.removeClass('btn-warning');
                    button.addClass('btn-success');
                    setTimeout( function(){
                        button.removeClass('btn-success disabled');
                        button.removeAttr('disabled');
                    }, 300 );
                }, 300 );

                $('#inputName').focus();

            });

            $('#aboutSend').click(function(){
                var button = $(this);
                button.addClass('disabled');
                button.attr('disabled','disabled');
                button.addClass('btn-warning');
                $('#aboutInfo').removeClass('label-warning label-success label-important');

                $('#inputName').blur();
                $('#inputEmail').blur();
                $('#inputComment').blur();
                $('#inputName').val(    _( $('#inputName').val().toLowerCase( ) ).chain().clean().trim().value() );
                $('#inputComment').val( _( $('#inputComment').val() ).chain().clean().trim().value() );

                if( $('#aboutForm .control-group.error').length > 0 ) {
                    $('#aboutForm .control-group.error :input').get(0).focus();
                    setTimeout( function(){
                        button.removeClass('btn-warning');
                        button.addClass('btn-danger');
                        setTimeout( function(){
                            button.removeClass('btn-danger disabled');
                            button.removeAttr('disabled');
                        }, 500 );
                    }, 300 );
                }
                else    {
                    var data = {};
                    $("#aboutForm .control-group.error :input").each( function( index, elem )   {
                        data[elem.name] = elem.value;
                    });

                    $('#aboutInfo').html( 'Enviando ...' );
                    $('#aboutInfo').addClass( 'label label-warning' );

                    $.post( "/services/contact", data, function( response )  {

                        $('#aboutInfo').removeClass( 'label-warning' );
                        $('#aboutInfo').addClass( ( response.error ? 'label-important' : 'label-success' ) );
                        $('#aboutInfo').html( response.message );

                        setTimeout( function(){
                            button.removeClass('btn-warning');
                            button.addClass('btn-success');
                            setTimeout( function(){
                                button.removeClass('btn-success disabled');
                                button.removeAttr('disabled');
                            }, 300 );
                        }, 300 );

                        if( !response.error )
                            $('#aboutClear').click();
                    }, 'json');
                }
            });

        }
    });
    // Returns the Router class
    return Desktop;
});