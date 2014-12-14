(function(){


	function init(){
		var page = document.URL.split('?page=')[1].split('&')[0];
		
		switch(page){
			case 'login':
				console.log('loginpage');
				break;

			case 'register':
				console.log('registerpage');
				break;

			case 'view':
				var WhiteboardApplication = require('./classes/WhiteboardApplication');
				new WhiteboardApplication(document.querySelector('.view'));
				break;

			case 'settings':
				var WhiteboardSettings = require('./classes/WhiteboardSettings');
				new WhiteboardSettings(document.querySelector('.settingspage'));
				break;
		}
	}

	init();


})();