(() => { 'use strict';
const SZN = window.SZN || {};
class PolymerElement {
  get behaviors() { // http://www.ericfeminella.com/blog/2016/03/25/polymer-behaviors-in-es6/
    return this._behaviors || (this._behaviors = [SZN.behaviors.App, SZN.behaviors.Firebase]);
  }
  set behaviors(value) { this._behaviors = value;}
  beforeRegister() { // Element setup goes in beforeRegister instead of createdCallback.
    this.is = 'szn-appdashboard';
    this.properties = { // Define the properties object in beforeRegister.
      users: {
          type: Object,
          notify: true
      },

      selected: {
        type: String,
        value: 'Item One'
      },

      wideLayout: {
        type: Boolean,
        value: false,
        observer: '_onLayoutChange',
      },
      routeParameters: {
        type: Object,
        notify: true
      },
      items: {
        type: Array,
        value: ()=> {
          return [
            {
              name: 'Files', route: 'files'
            },
            {
              name: 'Routes Map', route: 'routes'
            },
            {
              name: 'Documents', route: 'documents'
            },
            {
              name: 'Views', route: 'views'
            },
            {
              name: 'Global Settings', route: 'settings'
            }
          ];
        }
      }
    };
  }
  ready() {
    this.$.auth.signInWithCustomToken(this.app.firebase.customToken)
    .then( response => {// successful authentication response here
      console.log('successful Firebase sign-in.');
    })
    .catch( error => {// unsuccessful authentication response here
      console.log(error);
      console.log('Unsuccessful Firebase sign-in !');
    }); // Firebase authentication.
  }
  attached() {
  }
  _computeLabelId(uid) {
    return 'todoItemLabel-' + uid;
  }
  _onLayoutChange(wide) {
    var drawer = this.$.drawer;

    if (wide && drawer.opened) {
      drawer.opened = false;
    }
  }
  _login() {
    // var params;
    //
    // params.token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhZG1pbiI6ZmFsc2UsImRlYnVnIjpmYWxzZSwiZXhwIjozODAyNTUwNDAwLCJkIjp7InVpZCI6ImRlbnRyaXN0Y29tIn0sInYiOjAsImlhdCI6MTQ2MDU4NTg1OX0.RfGZ5Wub_5_y_S_3XpkTAoTZWMNzbCVZpG7DoWP-8T8';
    //
    // // try {
    // //   params = JSON.parse(this.params);
    // // } catch (e) {
    // //   params = null;
    // // }
    //
  }
  //
  // updateBinding(e) {
  //    this.set('data', e.target.textContent);
  // }
  _pushToFirebase(data) {
  }
}
HTMLImports.whenReady(()=>{
  Polymer(PolymerElement); // Register the element using Polymer's constructor.
});
})();
