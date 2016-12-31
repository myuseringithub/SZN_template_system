(()=>{ 'use strict';
  const Wordpress = {
  	// documentElementName is the variable for the name of the element to be preserved in adminpanel.
  	initiateDashboardInterfaceOnAdminPanel(documentElementName) {
  		// var mainDocumentDOMModuleClone = document.getElementById('main-document-element').cloneNode(true); // Clone
  		var mainDocumentDOMModuleClone = document.getElementById(documentElementName).cloneNode(true); // Clone
  		var mainDocumentElementClone = document.createElement(documentElementName);
  		// var appDashboardClone = document.getElementsByTagName('main-document-element').cloneNode(true); // Clone
  		// document.body.appendChild(appDashboardClone); // append to Body.
  		document.body.insertBefore(mainDocumentDOMModuleClone, document.body.firstChild); // Append to Body as first element.
  		// mainDocumentDOMModuleClone.appendChild(document.createElement('main-document-element'));;
  		var adminDashboardScripts = [].slice.call( document.getElementsByClassName("adminDashboardScripts") );
  		while (adminDashboardScripts.length > 0) {
  			SZN.behaviors.utilities.moveElementsToNewNode(adminDashboardScripts.shift(), mainDocumentDOMModuleClone);
  		}
  		SZN.behaviors.utilities.removeAllBodyChildrenExcept(mainDocumentDOMModuleClone); // Remove all other nodes.
  		document.body.insertBefore(mainDocumentElementClone, document.body.firstChild); // Append to Body as first element.
  	},
  	_getServerFiles(url, parameters) {	// List files in server using Wordpress Admin Ajax.

  		return new Promise((resolve, reject)=> {
  			fetch(url, {
  		    method: 'POST',
  				headers: {
  			    'Accept': 'application/json'
  			  },
  				credentials: 'same-origin', // By default, fetch won't send any cookies to the server, resulting in unauthenticated requests if the site relies on maintaining a user session.	https://github.com/github/fetch
  		    body: SZN.behaviors.utilities.convertObjectToFormData(parameters)
  		  })
  		  .then(SZN.behaviors.utilities.parseJSON)
  			.then( data => {
  		    resolve(data);
  		  })
  		  .catch( error => {
  		    console.log('Request failed', error);
  		  });
  		});
  	}
  };
  (window.SZN.behaviors = window.SZN.behaviors || {}).Wordpress = Wordpress;
})();
