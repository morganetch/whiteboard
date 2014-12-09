module.exports = (function(){

	function WhiteboardItem($el, data) {
		this.$el = $el;
		this.$editButton = this.$el.find('img[alt="settings"]');
		this.$dragBar = this.$el.find('div.border');
		this.$section = this.$el.find('section');
		this.data = data;

		// if(this.data.new){
		// 	console.log(this.$el.find('img[alt="settings"]'));
		// 	this.$el.find('img[alt="settings"]').trigger('click');
		// 	console.log(this.$el.find('img[alt="settings"]'));
		// }

		this.$editButton.on('click', this.editItemHandler.bind(this));
		this.$dragBar.on('mousedown', this.mousedownHandler.bind(this));
	}

	WhiteboardItem.prototype.mousedownHandler = function(event) {
		event.preventDefault();

		this.offsetX = event.offsetX;
		this.offsetY = event.offsetY;

		this._mousemoveHandler = this.mousemoveHandler.bind(this);
		this._mouseupHandler = this.mouseupHandler.bind(this);

		window.addEventListener('mousemove', this._mousemoveHandler );
		window.addEventListener('mouseup', this._mouseupHandler );
	};

	WhiteboardItem.prototype.mousemoveHandler = function(event) {
		this.$el.css('left', (event.x - this.offsetX) + "px");
		this.$el.css('top', (event.y - this.offsetY) + "px");

		if(this.$el.css('top').split('px')[0] < 40){
			this.$el.css('top', '40px');
		}


	};

	WhiteboardItem.prototype.mouseupHandler = function(event) {

		var boardId = document.URL.split('id=')[1];
		console.log(this.data.id);

		$.post( "index.php?page=save", { 
				id: this.data.id,
				x: this.$el.css('left'),
				y: this.$el.css('top'),
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

		this.$section.addClass('hidden');

		var template = Handlebars.compile( $('#edit-' + this.data.type + '-template').text());
		this.$el.append( $(template(this.data)));

		this.$submit = this.$el.find('input[type="submit"]');
		this.$submit.on('submit', this.formHandler.bind(this));

		this.$delete = this.$el.find('a');
		this.$delete.on('click', this.deleteClickHandler.bind(this));
	};

	WhiteboardItem.prototype.formHandler = function(event) {
		// event.preventDefault();
		this.form = this.el.querySelector('form');
		console.log(this.type);


		switch (this.type){
			case 1:

				break;

			case 2:

				break;

			case 3:

				// this.formData = {
				// 	title: this.form.querySelector('input[name="title"]').value,
				// 	content: this.form.querySelector('textarea[name="content"]').value,
				// 	desc: this.form.querySelector('textarea[name="desc"]').value
				// };

				break;
		}
		
		// $.ajax({
		//   type: "POST",
		//   url: 'index.php?page=view&id=' + this.boardId,
		//   data: this.formData,
		//   success: function(data) {
		//   	console.log('success');
  // 			bean.fire(this, 'update', this);

		//   	// console.log(data);
		//   	var html = data.split('<section class="holder">')[1];
		//   	var htmll = html.split('</section></section>');
		//   	console.log(htmll);
		//   },
		//   error: function(error) {
		//   	console.log('error');
		//   	console.log(error);
		//   }
		// });

	};


	WhiteboardItem.prototype.deleteClickHandler = function(event) {
		event.preventDefault();
		this.$el.remove();
		bean.fire(this, "delete", this);
	};

	WhiteboardItem.createItem = function(data){
		var source = $("#" + data.type + "-template").html();
		var template = Handlebars.compile(source);
		var html = template(data);
		return new WhiteboardItem($(html), data);

	}

	return WhiteboardItem;
})();