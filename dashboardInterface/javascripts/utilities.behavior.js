(()=>{ 'use strict';
  const utilities = {
    properties: {
    },
    attached() {
    },
    detached() {
    },
    loadJavascriptToDocumentHead(source, onload, onerror) { //https://github.com/Polymer/polymer/issues/3070
      var scriptNode = document.createElement('script');
      scriptNode.src = source;
      var self = this;
      if (onload) {
        scriptNode.onload = e => {
          return onload.call(self, e);
        }
      }
      if (onerror) {
        scriptNode.onerror = e => {
          return onerror.call(self, e);
        }
      }
      document.head.appendChild(scriptNode);
      return scriptNode;
    },
    moveElementsToNewNode(oldElementsParent, newElementsParent) {
      // was used in case
      // var newElementsParent = document.createElement('div');
      // document.body.appendChild(newElementsParent);

      while (oldElementsParent.childNodes.length > 0) {
          newElementsParent.appendChild(oldElementsParent.childNodes[0]);
      }
      // newElementsParent.style.attributes = oldElementsParent.style.cssText;
      // newElementsParent.id = oldElementsParent.id;
      // oldElementsParent.remove(); // remove old element
    },
    removeAllBodyChildrenExcept(exceptionElement) {
      var parentNodeChildren = document.body.children;
      for (var i = 0; i < parentNodeChildren.length; i++) {
        if (parentNodeChildren[i] != exceptionElement){
          parentNodeChildren[i].remove();
        }
      }
    },
  	importElements(pluginURLPath, elementsFilesArray) {
  		for (var i=0; i < elementsFilesArray.length; i++) {
  			Polymer.Base.importHref(pluginURLPath + '/dashboardInterface/' + elementsFilesArray[i], (e)=> {}, (e)=> { /* loading error */});
  		}
  	},
  	jointPaths(parts, sep) {
  		// there is also [y, x, z].join("/")  // but it doesn't take into account slash duplicates.
  	   var separator = sep || '/';
  	   var replace   = new RegExp(separator+'{1,}', 'g');
  	   return parts.join(separator).replace(replace, separator);
  	},
  	parseHTMLToElementNode(documentFragment) {
  		// parse HTML string to node element.
  		var template = document.createElement('template');
  		template.innerHTML = documentFragment;
  		return template.content.cloneNode(true);
  	},
  	parseJSON(response) {	// parse JSON response. used in .then of promise.
  		return response.json();
  	},
  	convertObjectToFormData(query) {	// transform parameters to form data. // WARNING - this will not work on GET or Head. http://stackoverflow.com/questions/34692554/url-query-string-in-fetch-api-in-javascript
  			var formData = new FormData();
  			for (var key in query) {
  					formData.append(key, query[key]);
  			}
  			return formData;
  	},
  	addProtocolHostToURLPath(urlPath) {	// add the https and domain name to the url path.
  		var http = location.protocol;
  		var slashes = http.concat("//");
  		var host = slashes.concat(window.location.hostname);
  		return host + urlPath;
  	}


  };
  (window.SZN.behaviors = window.SZN.behaviors || {}).utilities = utilities;

})();
