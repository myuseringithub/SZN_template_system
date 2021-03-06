(() => { 'use strict';
const SZN = window.SZN || {};
class PolymerElement {
  get behaviors() { // http://www.ericfeminella.com/blog/2016/03/25/polymer-behaviors-in-es6/
    return this._behaviors || (this._behaviors = [SZN.behaviors.App, SZN.behaviors.Firebase]);
  }
  set behaviors(value) { this._behaviors = value;}
  beforeRegister() { // Element setup goes in beforeRegister instead of createdCallback.
    this.is = 'settings-view';
    this.properties = { // Define the properties object in beforeRegister.
      locations: {
          type: Array,
          notify: true
      }
    };
  }
  ready() {
  }
  attached() {
  }
}
HTMLImports.whenReady(()=>{
  Polymer(PolymerElement); // Register the element using Polymer's constructor.
});
})();
