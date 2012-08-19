define(['jquery','backbone','views/desktop/init','views/desktop/home', 'text!templates/desktop/about.html'], function($, Backbone, InitView, HomeView, aboutText ){
    var Router = Backbone.Router.extend({
        initialize: function(){
            // Tells Backbone to start watching for hashchange events
            Backbone.history.start();
            $("#main").html( aboutText );
        },
        // All of your Backbone Routes (add more)
        routes: {
            // When there is no hash bang on the url, the home method is called
            '': 'init',
            '_=_': 'redirect',
            'home': 'home'
        },
        'home': function(){
            console.log( "home called" );
            // Instantiating mainView and anotherView instances
            var homeView = new HomeView();
            // Renders the mainView template
            homeView.render();
        },
        'redirect': function(){
            console.log( "redirect called" );
            var initView = new InitView( { code:this.getRequest('code') } );
            initView.render();
        },
        'init': function()  {
            console.log( "init called" );
            var initView = new InitView();
            initView.render();
        },
        'getRequest':function( name ){
            return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
        }
    });
    // Returns the Router class
    return Router;
});