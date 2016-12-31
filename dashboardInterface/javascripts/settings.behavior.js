(()=>{ 'use strict';
// window.SZN = window.SZN || {};
// SZN.behaviors.settings = SZN.behaviors.settings || { // With this syntax - even if called several times it will set behaviors once.
  const Settings = { // define behavior.
    properties: {
      /**
       * App logic
       */
       app: {
         type: Object,
  			 notify: true,
         value: ()=>{
           return {
  					 	parameters:	{
  						},
         			locations: {
         				pluginURLPath: SZN.App.locations.pluginURLPath,
         				appPath: SZN.App.locations.appPath,
  							wordpressAdminAjaxUrl:	ajaxurl // (without domain name) a wordpress parameter that exists inside admin area.
         			},
  						firebase: SZN.App.locations.firebase
         	};
         }
       }
    },
    attached() {
    },
    detached() {
    },
  	_authErrorHandler(e) {
  		console.log(e);
  	}
  };

  (window.SZN.behaviors = window.SZN.behaviors || {}).settings = Settings; // add the behavior to a specific namespace.
})();
