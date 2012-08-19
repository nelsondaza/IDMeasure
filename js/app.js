/* Author:
*/

// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function f(){ log.history = log.history || []; log.history.push(arguments); if(this.console) { var args = arguments, newarr; args.callee = args.callee.caller; newarr = [].slice.call(args); if (typeof console.log === 'object') log.apply.call(console.log, console, newarr); else console.log.apply(console, newarr);}};
// make it safe to use console.log always
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
    (function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

// Sets the require.js configuration.
require.config({
    baseUrl: 'js/',
    // 3rd party script alias names (Easier to type "libs/jquery" than "js/libs/jquery-1.7.2.min")
    paths: {
        // Core Libraries
        modernizr: "libs/modernizr-2.6.1",
        jquery: "libs/jquery-1.7.2",
        underscore: "libs/underscore-1.3.3",
        backbone: "libs/backbone-0.9.2",
        // Require.js Plugins
        text: "plugins/text-2.0.3"
    },
    // Sets the configuration for your third party scripts that are not AMD compatible
    shim: {
        "jquery": {
            exports: "$"  //attaches "Backbone" to the window object
        },
        "underscore": {
            exports: "_"  //attaches "Backbone" to the window object
        },
        "backbone": {
            deps: ["underscore", "jquery"],
            exports: "Backbone"  //attaches "Backbone" to the window object
        }
    } // end Shim Configuration
});

// Include Desktop Specific JavaScript files here (or inside of your Desktop router)
require(['modernizr','jquery','backbone','routers/Desktop'], function(Modernizr, $, Backbone, Desktop) {

    var offset = new Date().getTimezoneOffset() / -60;
    document.cookie = 'timezoneOffset=' + encodeURIComponent(offset);

    // Instantiates a new Router
    this.router = new Desktop();
});

