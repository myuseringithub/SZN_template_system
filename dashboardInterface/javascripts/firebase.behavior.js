(()=>{ 'use strict';
  const Firebase = {
  	_filterToPreventInputUnfocusOnFirebase(d) { // for use in 'template repeat' together with firebase which will prevent re stamping/removing and updating  of template.
  		return item => {
  			return true;
  		};
  	},
  	_toArrayAndRemoveKey(obj) { // change object to array without preservation of key.
  				// and remove __firebaseKey__
  				// Object.keys(obj).filter((key)=> {
  				//     return key != '__firebaseKey__';
  				// });

  				return Object.keys(obj).map( key => {
  						return obj[key];
  				});
  		},
  		_retrieveFiles(path) {	// retrieve Files list of a specific type.

  				return new Promise((resolve, reject)=> {
  					var ref = firebase.database().ref(path).once('value').then( snapshot => {
  						resolve(snapshot.val());
  					});
  				});

  			// var getValue = (path)=> {
  			//   return new Promise((resolve, reject) => {
  			//     firebase.database().ref(path).once('value').then(snapshot => {
  			//       if (!snapshot /* or whateer*/) {
  			//         reject(error);
  			//       } else {
  			//         resolve(snapshot.val());
  			//       }
  			//     })
  			//   });
  			// };
  			//
  			// var main = (path)=> {
  			//   try {
  			//     var value = getValue(path);
  			//     console.log(value);
  			//   } catch(error) {
  			//     console.error(error);
  			//   }
  			// };
  			//
  			// main(path);

  		}
  };
  (window.SZN.behaviors = window.SZN.behaviors || {}).Firebase = Firebase;
})();
