module.exports = (function(){

	var WhiteboardItem = require('./WhiteboardItem');

	function WhiteboardForm(el) {
		this.el = el;

		var $image = $(this.el).find('input[id="image-upload"]');
		$image.on('change', this.imageValidation.bind(this));

	}

	WhiteboardForm.prototype.imageValidation = function() {
		console.log("validatie");

		if (window.File && window.FileReader && window.FileList && window.Blob) {
			var file = this.el.querySelector('input[id="image-upload"]').files[0];
			if(file.type.indexOf('image') === 0){
				// var $form = $(this.el).find('input[id="image-upload"]');
				// $form.parent().parent().submit();
				var form = this.el.querySelector('form');
				console.log(form);


				// $.post("index.php?page=image", { 
				// 		left: (window.innerWidth/2)-140 +"px",
				// 		top: (window.innerHeight/2)-145 +"px",
				// 		type: 1,
				// 		title: 'Image',
				// 		description: 'Hier komt een korte omschrijving',
				// 		content: content,
				// 	})
				//   	.done(function(data) {
				//     // console.log(data);
				//    	// if(data.result) {
				//    	// 	voorbeeldJSONGet();
				//    	// } else {

				//    	// }
				//   });
			}
		}


		
	};

	return WhiteboardForm;
})();