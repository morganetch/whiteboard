module.exports = (function(){

	var WhiteboardItem = require('./WhiteboardItem');

	function WhiteboardForm(el) {
		this.el = el;

		var $image = $(this.el).find('input[id="image-upload"]');
		$image.on('change', this.imageValidation.bind(this));

		var $video = $(this.el).find('input[id="video-upload"]');
		$video.on('change', this.videoValidation.bind(this));

	}

	WhiteboardForm.prototype.imageValidation = function(event) {

		if (window.File && window.FileReader && window.FileList && window.Blob) {
			var file = this.el.querySelector('input[id="image-upload"]').files[0];
			if(file.type.indexOf('image') === 0){
				var $submitbutton = $(this.el).find('input[value="image"]');
				$submitbutton.click();

				// $.post("index.php?page=image", { 
				// 		left: (window.innerWidth/2)-140 +"px",
				// 		top: (window.innerHeight/2)-145 +"px",
				// 		type: 1,
				// 		title: 'Image',
				// 		description: 'Hier komt een korte omschrijving',
				// 		content: content,
				// 	})
				//   	.done(function(data) {
				//     
				//   });
			}
		}


		
	};

	WhiteboardForm.prototype.videoValidation = function(event) {
		event.preventDefault();
		var file = this.el.querySelector('input[id="video-upload"]').files[0];
		if(file.type.indexOf('video') === 0){
			var $submitbutton = $(this.el).find('input[value="video"]');
			$submitbutton.click();
		}
	};

	return WhiteboardForm;
})();