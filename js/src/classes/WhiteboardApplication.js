module.exports = (function(){

	var WhiteboardItem = require('./WhiteboardItem');

	function WhiteboardApplication(el) {
		this.el = el;
		this.addPicture = el.querySelector('a[href="1"]');
		this.addVideo = el.querySelector('a[href="2"]');
		this.addText = el.querySelector('a[href="3"]');

		this.items = this.el.querySelectorAll('.item');
		this.whiteboardItems = [];
		this.$itemsList = $(el).find('.holder');
		

		for(var i = 0; i < this.items.length; i++){
			this.addItemPreparer(this.items[i]);
		}

		$(this.items).remove();

		$(this.addText).on('click', this.makeNewItem.bind(this));
		$(this.addPicture).on('click', this.makeNewItem.bind(this));
		$(this.addVideo).on('click', this.makeNewItem.bind(this));

	}

	WhiteboardApplication.prototype.addItemPreparer = function(article) {

		var section = article.querySelector('section');
		var content;

		switch(parseInt(section.id)){
			case 1:
				content = section.querySelector('img[src^="uploads"]').getAttribute('src');
				break;

			case 2:
				content = section.querySelector('video').getAttribute('src');
				break;

			case 3:
				content = section.querySelector('p').innerText;
				break;
		}

		var data = {
			left: article.style.left,
			top: article.style.top,
			z: article.style.zIndex,
			id: article.id,
			type: section.id,
			title: section.querySelector('h1').innerText,
			description: section.querySelector('.desc').innerText,
			content: content,
			new: false
		};

		this.addItemHandler(data);
	};

	WhiteboardApplication.prototype.makeNewItem = function(event) {
		event.preventDefault();
		var type, content, title;

		if(event.target.getAttribute('alt')){
			type = event.target.parentNode.getAttribute('href');
		} else {
			type = event.target.getAttribute('href');
		}

		switch(parseInt(type)){
			case 1:
				title = 'Nieuwe foto';
				content = 'uploads/No_image_available.png';

				break;

			case 2:
				title = 'Nieuwe video';
				content = '';
				break;

			case 3:
				title = 'Nieuw tekstje';
				content = 'Omschrijving van uw tekstje';
				console.log("die");
				break;
		}

		var data = {
			left: (window.innerWidth/2)-140 +"px",
			top: (window.innerHeight/2)-145 +"px",
			id: 5,
			type: type,
			title: title,
			description: 'Hier komt een korte omschrijving',
			content: content,
			new: true
		};

		this.addItemHandler(data);
	};

	WhiteboardApplication.prototype.addItemHandler = function(data) {
		// console.log(data);
		var item = WhiteboardItem.createItem(data);
		bean.on(item, "delete", this.deleteItemHandler.bind(this));
		this.whiteboardItems.push(data);
		this.$itemsList.append(item.$el);
	};

	WhiteboardApplication.prototype.deleteItemHandler = function(item) {
		this.whiteboardItems.splice(this.whiteboardItems.indexOf(item), 1);
		console.log("spliced");
	};

	WhiteboardApplication.prototype.updateItems = function() {
		
		// for(var i = 0; i < this.items.length; i++){
		// 	this.addItem(this.items[i]);
		// }
	};

	return WhiteboardApplication;
})();