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
            this.template = template;
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