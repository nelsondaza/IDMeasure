define(['jquery','backbone','views/desktop/init','views/desktop/home'], function($, Backbone, InitView, HomeView){
    var Router = Backbone.Router.extend({
        initialize: function(){
            // Tells Backbone to start watching for hashchange events
            Backbone.history.start();
        },
        // All of your Backbone Routes (add more)
        routes: {
            // When there is no hash bang on the url, the home method is called
            '': 'init',
            '_=_': 'redirect',
            'X': 'home'
        },
        'home': function(){
            // Instantiating mainView and anotherView instances
            var homeView = new HomeView();
            // Renders the mainView template
            homeView.render();
        },
        'redirect': function(){
            var initView = new InitView( { code:this.getRequest('code') } );
            initView.render();
        },
        'init': function()  {
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