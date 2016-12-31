<script>
// Initialize the window object 'SZN' That will contain all utility functions and behaviors for the app //
(exports => { 'use strict';
	// Global Object that handles the app and other stuff.
	exports.SZN = exports.SZN || {};
	exports.SZN.behaviors = exports.SZN.behaviors || {}; // polymer behaviors that include functions and properties.
})(window);

// ############################################################## //
// (function(window) {
// 'use strict';
// window.SZN = window.SZN || {};
//
// 	// as if this pattern is used to create a class like variable. It allows attributing a returned object after using encapsulated system of functions and variable manipulations.
// 	SZN.Example = SZN.Example || (function() {
// 		'use strict';
// 		function functionName() {
// 		}
// 		return {
// 	    functionName
// 	  };
// 	})();
//
// })();
// ############################################################## //

(() => { 'use strict';
	(window.SZN = window.SZN || {}).App = class {
	  static get locations() {
	      return {
	        pluginURLPath: "<?= \SZN\App::$plugin_directory_url ?>",
	        appPath: "<?= \SZN\App::join_paths( \SZN\App::$appPath ) ?>",
					firebase: {
						basePath: (()=>{
							var concatenatedHostname = (window.location.hostname).split('.').join("");
							return '/domains/' + concatenatedHostname;
						})(),
						baseURL: 'https://intense-heat-1283.firebaseio.com/',
						apiKey: 'AIzaSyC3fvxWvgy1Rr7_jlAmC6bbx-i-pKxR4-A',
						authDomain: 'intense-heat-1283.firebaseapp.com',
						databaseURL: 'https://intense-heat-1283.firebaseio.com',
						customToken: (()=>{
							return "<?= \SZN\App::join_paths( \SZN\App::$locations['firebase']['frontendToken'] ) ?>";
						})()
					}
	      };
	  }
	  constructor() {
	  }
	  static distance(a, b) {
	      const dx = a.x - b.x;
	      const dy = a.y - b.y;
	      return Math.sqrt(dx*dx + dy*dy);
	  }
		static scriptLoader(url) { // promise based loader - https://bradb.net/blog/promise-based-js-script-loader/
		    if(Array.isArray(url)) {
		        var self = this, prom = [];
		        url.forEach(function(item) {
		            prom.push(self.scriptLoader(item));
		        });
		        return Promise.all(prom);
		    }
		    return new Promise((resolve, reject) => {
		        var r = false,
		            t = document.getElementsByTagName("script")[0],
		            s = document.createElement("script");
		        s.type = "text/javascript";
		        s.src = url;
		        s.async = true;
		        s.onload = s.onreadystatechange = () => {
		            if (!r && (!this.readyState || this.readyState == "complete")) {
		                r = true;
		                resolve(this);
		            }
		        };
		        s.onerror = s.onabort = reject;
		        t.parentNode.insertBefore(s, t);
		    });
		}
		// function loadScript(url, callback)
		// {
		//     // Adding the script tag to the head as suggested before
		//     var head = document.getElementsByTagName('head')[0];
		//     var script = document.createElement('script');
		//     script.type = 'text/javascript';
		//     script.src = url;
		//     // Then bind the event to the callback function.
		//     // There are several events for cross browser compatibility.
		//     script.onreadystatechange = callback;
		//     script.onload = callback;
		//     // Fire the loading
		//     head.appendChild(script);
		// }
	}
})();
</script>
