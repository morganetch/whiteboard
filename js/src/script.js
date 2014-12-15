(function(){


	function init(){

		var view = document.querySelector('.view');
		var settings = document.querySelector('.settingspage');

		if(view){
			var WhiteboardApplication = require('./classes/WhiteboardApplication');
			new WhiteboardApplication(view);
		} else if(settings) {
			var WhiteboardSettings = require('./classes/WhiteboardSettings');
			new WhiteboardSettings(settings);
		}
	}

	init();


})();