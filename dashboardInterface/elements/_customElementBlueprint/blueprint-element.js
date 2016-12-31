(() => { 'use strict';
const SZN = window.SZN || {};
class PolymerElement {
  get behaviors() { // http://www.ericfeminella.com/blog/2016/03/25/polymer-behaviors-in-es6/
    return this._behaviors || (this._behaviors = []);
  }
  set behaviors(value) { this._behaviors = value;}
  beforeRegister() { // Element setup goes in beforeRegister instead of createdCallback. Allow to transform an element's prototype
    this.is = 'X';
    this.hostAttributes = {
      //If a custom element needs HTML attributes set on it at create-time.
    }

    this.properties = { // Define the properties object in beforeRegister.
    };
  }
  registered() {
    // perform one-time initialization when an element is registered.
  }
  factoryImpl(foo, bar) { // create custom instance imperatively with specific custom attributes.
    this.foo = foo;
    this.configureWithBar(bar);
  },
  ready() {
  }
  attached() {
    this.async(function() {
      // access sibling or parent elements here. because There are no guarantees with regard to initialization timing between sibling elements.
    });
  }
}
HTMLImports.whenReady(()=>{ // define element in main document.
  Polymer(PolymerElement); // Register the element using Polymer's constructor.
});
})();
