var thisScriptUrl = document.currentScript.src;
if ('serviceWorker' in navigator) {
	window.addEventListener('load', function() {
	  const url = thisScriptUrl.split("/").slice(0,-2).join("/")+'/sw.js';
	  navigator.serviceWorker.register(url).then(function(registration) {
		// Registration was successful
		console.log('ServiceWorker registration successful with scope: ', registration.scope);
	  }, function(err) {
		// registration failed :(
		console.log('ServiceWorker registration failed: ', err);
	  });
	});
  }