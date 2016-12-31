(() => { 'use strict';
const SZN = window.SZN || {};
class PolymerElement {
  get behaviors() { // http://www.ericfeminella.com/blog/2016/03/25/polymer-behaviors-in-es6/
    return this._behaviors || (this._behaviors = [SZN.behaviors.App, SZN.behaviors.Firebase]);
  }
  set behaviors(value) { this._behaviors = value;}
  beforeRegister() { // Element setup goes in beforeRegister instead of createdCallback.
    this.is = 'files-view';
    this.properties = { // Define the properties object in beforeRegister.
      files: {
          type: Array,
          notify: true
      },
      filesTypes: {
          type: Array,
          notify: true,
          observer: 'filesTypesChanged'
      },
      filename: {
        type: String,
        notify: true
      },
      directory: {
        type: String,
        notify: true
      },
      params: {
        type: Object,
        notify: true,
        value: ()=> {
          return {};
        }
      }
    };
  }
  ready() {
    // get templates from szn_ajaxCheckTemplateFiles.
    var promises = [];

    // Firebase data
    var path = this.app.firebase.basePath + '/' + 'files/templates';
    var promise = this._retrieveFiles(path);
    promises.push(promise);

    // Server data https://dentrist.com/site/wp-admin/admin-ajax.php?action=szn_ajaxCheckTemplateFiles
    var promise = this._getServerFiles(this.app.locations.wordpressAdminAjaxUrl, {action: 'szn_ajaxCheckTemplateFiles'});
    promises.push(promise);

    Promise.all(promises).then(values => {
      var templatesFirebase = values[0];
      var templatesServer = values[1];
      // console.log(templatesServer);
      // console.log(templatesFirebase);
    }).catch(() => {
      // console.log('Error in promise all.');
    });
  }
  attached() {
  }
  filesTypesChanged() {
    // nothing works.
    // this.$.appdrawerlayout.resetLayout();
    // this.$.appheaderlayout.resetLayout();
    // this.$.appheader.resetLayout();
    // window.dispatchEvent(new Event('resize'));
  }
  // AJAX
  _ajaxCreateFile() {
    this._getajaxparams();
    this.$.ajaxCreateFile.generateRequest();
  }
  _ajaxCheckTemplateFiles() {
    this.$.ajaxCheckTemplateFiles.generateRequest();
  }
  _getajaxparams() {
    this.params.action = "szn_ajaxcreatefile";
    this.params.file = this.jointPaths([this.app.locations.appPath, this.directory, this.filename]);
  }
  // get ajaxurl() {
  //   return ajaxurl; // a wordpress parameter that exists inside admin area.
  // }
  _handleResponse(data) {
    if(data.detail.response) {
      console.log('file created');
    } else {
      console.log('cannot create');
    }
  }
  _handleResponseTemplates(data) {
    console.log(data.detail.response);
  }
  _pushTemplates(data) { // get templates list from ajax request from the server and push them to the templates path in Firebase.
    var host = this
    var templates = data.detail.response;
    // templates = [{"templateFile": "archive.template.php", "insertionPositions": ["x","y"]}]; // for testing
    var path = host.jointPaths([ this.app.firebase.basePath, 'files/templates' ]);
    templates.forEach(template => {
      var insertionPositions = template.insertionPositions;
      delete template.insertionPositions;
      // push main template args templateFile.
      var ref = firebase.database().ref(path).push(template);
      // add insertionPositions
      var insertionPositionsPath = host.jointPaths([ path, ref.key, 'insertionPositions' ]);
      if(insertionPositions) {
        insertionPositions.forEach(insertionPosition => {
          firebase.database().ref(insertionPositionsPath).push(insertionPosition);
        });
      }
      console.log(ref.key);
    });
  }
  _filesTypeNumber(filestypenumber){
    return this.files.filestypenumber;
  }
  // If enter was pressed, unfocus the text input
  _checkConfirmation(e) {
    if (e.keyCode === 13) {
      e.preventDefault();
      e.target.blur();
    }
  }

}
HTMLImports.whenReady(()=>{
  Polymer(PolymerElement); // Register the element using Polymer's constructor.
});
})();
