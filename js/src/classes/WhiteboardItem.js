module.exports = (function(){

	function WhiteboardItem($el, data) {
		this.$el = $el;
		this.$editButton = this.$el.find('img[alt="settings"]');
		this.$dragBar = this.$el.find('div.border');
		this.$section = this.$el.find('section');
		this.data = data;

		this.$editButton.on('click', this.editItemHandler.bind(this));
		this.$dragBar.on('mousedown', this.mousedownHandler.bind(this));
	}

	WhiteboardItem.prototype.mousedownHandler = function(event) {
		event.preventDefault();

		this.$el.css('zIndex', this.getHighestZ);
		this.offsetX = event.offsetX;
		this.offsetY = event.offsetY;

		this._mousemoveHandler = this.mousemoveHandler.bind(this);
		this._mouseupHandler = this.mouseupHandler.bind(this);

		window.addEventListener('mousemove', this._mousemoveHandler );
		window.addEventListener('mouseup', this._mouseupHandler );
	};

	WhiteboardItem.prototype.mousemoveHandler = function(event) {
		this.$el.css('left', (event.pageX - this.offsetX) + "px");
		this.$el.css('top', (event.pageY - this.offsetY) + "px");

		var top = this.$el.css('top').split('px')[0];
		var left = this.$el.css('left').split('px')[0];
		var width = this.$el.css('width').split('px')[0];
		var height = this.$el.css('height').split('px')[0];
		var right = parseInt(left) + parseInt(width);
		var bottom = parseInt(top) + parseInt(height);
		var windowWidth = window.innerWidth + 'px';
		var windowHeight = window.innerHeight + 'px';

		if(top < 40){
			this.$el.css('top', '40px');
		}

		if(left < 0){
			this.$el.css('left', '0px');
		}

		if(right > window.innerWidth){
			this.$el.css('left', parseInt(windowWidth)-parseInt(width));
		}

		if(bottom > window.innerHeight){
			this.$el.css('top', parseInt(windowHeight)-parseInt(height));
		}
	};

	WhiteboardItem.prototype.mouseupHandler = function(event) {

		var boardId = document.URL.split('id=')[1];
		console.log(this.data.id);

		$.post("index.php?page=save", { 
				id: this.data.id,
				x: this.$el.css('left'),
				y: this.$el.css('top'),
				z: this.$el.css('zIndex'),
				boardId: boardId
			})
		  	.done(function(data) {
		    console.log(data);
		   	// if(data.result) {
		   	// 	voorbeeldJSONGet();
		   	// } else {

		   	// }
		  });

		window.removeEventListener('mousemove', this._mousemoveHandler);
		window.removeEventListener('mouseup', this._mouseupHandler);
	};

	WhiteboardItem.prototype.editItemHandler = function(event) {
		event.preventDefault();

		var templateType;
		if(this.data.type == 1 || this.data.type == 2){
			templateType = 1;
		} else {
			templateType = 2;
		}

		this.$section.addClass('hidden');

		var template = Handlebars.compile( $('#edit-' + templateType + '-template').text());
		this.$el.append( $(template(this.data)));

		this.$form = this.$el.find('form[value="updateText"]');
		this.$form.on('submit', this.formHandler.bind(this));

		// this.$delete = this.$el.find('a');
		// this.$delete.on('click', this.deleteClickHandler.bind(this));
	};

	WhiteboardItem.prototype.formHandler = function(event) {
		// event.preventDefault();
		// console.log("submit");

		// this.form = this.el.querySelector('form');
		// console.log(this.data.type);


		// switch (this.type){
		// 	case 1:

		// 		break;

		// 	case 2:

		// 		break;

		// 	case 3:

		// 		// 



		// 		break;
		// }

		// $.post( "index.php?page=edit", 
				// 	{ 
				// 		id: this.data.id,
				// 		title: this.form.querySelector('input[name="title"]').value,
				// 		content: this.form.querySelector('textarea[name="content"]').value,
				// 		desc: this.form.querySelector('textarea[name="desc"]').value,
				// 		boardId: boardId
				// 	}).done(function(data) {
				//     // console.log(data);
				//    	// if(data.result) {
				//    	// 	voorbeeldJSONGet();
				//    	// } else {

				//    	// }
				//   	});
	};


	WhiteboardItem.prototype.deleteClickHandler = function(event) {
		event.preventDefault();
		this.$el.remove();
		bean.fire(this, "delete", this);
		console.log('clickhandler');
		$.post("index.php?page=deleteitem", { 
				id: this.data.id
			})
		  	.done(function(data) {
		    // console.log(data);
		   	// if(data.result) {
		   	// 	voorbeeldJSONGet();
		   	// } else {

		   	// }
		  });
	};

	WhiteboardItem.createItem = function(data){
		var source = $("#" + data.type + "-template").html();
		var template = Handlebars.compile(source);
		var html = template(data);
		return new WhiteboardItem($(html), data);

	};

	WhiteboardItem.prototype.getHighestZ = function() {
		
		var zIndexes = [];

		var $items = $('.holder').find('article');
		$items.each(function(index, element){
			zIndexes.push($(element).css('zIndex'));
		});

		var highest = Math.max.apply(null, zIndexes);

		return highest+1;
	};

	return WhiteboardItem;
})();