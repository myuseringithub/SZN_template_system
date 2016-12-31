<?php  // Using AdminArea.class.php this file gets added to the wordpress admin area, together with javascript which binds the main document element to the page.
// Allow for PHP usage in main document.
?>


<!-- main document element is placed in side the php main file to allow server-side manipulations. -->
<dom-module id="main-document-element">
  <template>
    <szn-appdashboard></szn-appdashboard>
  </template>
  <script>
  (() => { 'use strict';
    const SZN = window.SZN || {};
    class MainDocumentElement {
      get behaviors() { // http://www.ericfeminella.com/blog/2016/03/25/polymer-behaviors-in-es6/
        return this._behaviors || (this._behaviors = [SZN.behaviors.App]);
      }
      set behaviors(value) { this._behaviors = value;}
      beforeRegister() { // Element setup goes in beforeRegister instead of createdCallback.
        this.is = 'main-document-element';
        this.properties = { // Define the properties object in beforeRegister.
        };
      }
      ready() {
        this.importElements(this.app.locations.pluginURLPath,   [ // Order of imports is important !
          'elements/szn-span.html',
          'elements/szn-card.html',
          'elements/_designPrototypes/szn-card-prototype.designPrototype.html',
          'elements/szn-addfilecard/szn-addfilecard.html',
          'elements/szn-appdashboard/szn-appdashboard.html',
          'elements/files-view/files-view.html',
          'elements/routes-view/routes-view.html',
          'elements/settings-view/settings-view.html',
          'elements/documents-view/documents-view.html',
          'elements/views-view/views-view.html',
          'elements/szn-routecard/szn-routecard.html',
          'elements/szn-filecard/szn-filecard.html',
          'elements/szn-fileslist/szn-fileslist.html'
        ]);
        // this.loadJavascriptToDocumentHead('https://www.gstatic.com/firebasejs/live/3.0/firebase.js');
        // // Dynamically import element - http://stackoverflow.com/questions/23663487/polymer-how-to-dynamically-import-an-element
      }
      attached() {
        // Now moved to attached. Old comment -Not sure why js should be loaded before html. because html dom-module should be parsed before Polymer({}) call.
        // System.import(this.app.locations.pluginURLPath + '/dashboardInterface/' + 'javascripts/szn-appdashboard.js');
        // e.target.import is the import document.
        // var SZNAppDashboard = this.$.SZNAppDashboard;
        // var SZNAppDashboard = document.createElement('szn-appdashboard');
        // var SZNAppDashboard = Polymer.dom(this.root).querySelector("szn-appdashboard");
        // newElement.myProperty = 'foo';
        // http://stackoverflow.com/questions/23740548/how-to-pass-variables-and-data-from-php-to-javascript
        // SZNAppDashboard.app = this.app; // add property. for some reason decleratively adding property doesn't work.
        // Polymer.dom(this.root).appendChild(SZNAppDashboard);
      }

      // Define other lifecycle methods as you need
      // registered() {}
      // created() {}
      // ready() {}
      // factoryImpl() {}
      // attached() {}
      // detached() {}
      // attributChanged(){}

    }
    HTMLImports.whenReady(()=>{
      Polymer(MainDocumentElement); // Register the element using Polymer's constructor.
    });
  })();
  </script>
</dom-module>

<script>
  HTMLImports.whenReady(()=> {
    // creates main-document-element node & moves removes all other admin page panel elements.
  	SZN.behaviors.Wordpress.initiateDashboardInterfaceOnAdminPanel('main-document-element');	// Remove elements and insert dashboard interface elements instead.
  });
</script>
