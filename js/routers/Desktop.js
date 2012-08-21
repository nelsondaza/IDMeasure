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

            $("#main").html( aboutText );
            $('#navbar').scrollspy( );

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
            console.log( "about called" );
            $("#main").html( aboutText );
        }
    });
    // Returns the Router class
    return Desktop;
});