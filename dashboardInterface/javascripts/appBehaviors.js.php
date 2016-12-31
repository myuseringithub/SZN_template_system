<script>
(()=>{ 'use strict';
const SZN = (window.SZN = window.SZN || {});
(SZN.App.scriptLoader([
  SZN.App.locations.pluginURLPath + '/dashboardInterface/' + 'javascripts/utilities.behavior.js',
  SZN.App.locations.pluginURLPath + '/dashboardInterface/' + 'javascripts/settings.behavior.js',
  SZN.App.locations.pluginURLPath + '/dashboardInterface/' + 'javascripts/wordpress.behavior.js',
  SZN.App.locations.pluginURLPath + '/dashboardInterface/' + 'javascripts/firebase.behavior.js',
])).then(() => {
   /**
   `SZN.behavior.App` provides common functionality and util methods for the app.
   @polymerBehavior SZN.behaviors.App
   */
   // THis is the right way to do so, from the Polymer documentation. But in classes it doesn't work.
   (window.SZN.behaviors = window.SZN.behaviors || {}).App = [SZN.behaviors.settings, SZN.behaviors.utilities, SZN.behaviors.Firebase, SZN.behaviors.Wordpress];
});

})();
</script>
