module.exports = (function(){

	function WhiteboardItem(el) {
		this.el = el;
		this.buttons = this.el.querySelectorAll('a');
		this.editButton = this.buttons[0];
		this.deleteButton = this.buttons[1];
		this.el.addEventListener('mousedown', this.mousedownHander.bind(this));
		this.editButton.addEventListener('click', this.editItemHander.bind(this));
		this.deleteButton.addEventListener('click', this.deleteItemHander.bind(this));
		
	}

	WhiteboardItem.prototype.mousedownHander = function(event) {
		event.preventDefault();
	
		if(event.y < parseInt(getComputedStyle(event.path[0])['top'].split('p')[0])+15){
			this.offsetX = event.offsetX;
			this.offsetY = event.offsetY;

			this._mousemoveHandler = this.mousemoveHandler.bind(this);
			this._mouseupHandler = this.mouseupHandler.bind(this);

			window.addEventListener('mousemove', this._mousemoveHandler );
			window.addEventListener('mouseup', this._mouseupHandler );
		}
	};

	WhiteboardItem.prototype.mousemoveHandler = function(event) {
		this.el.style.left = (event.x - this.offsetX) + "px";
		this.el.style.top = (event.y - this.offsetY) + "px";
	};

	WhiteboardItem.prototype.mouseupHandler = function(event) {
		window.removeEventListener('mousemove', this._mousemoveHandler);
		window.removeEventListener('mouseup', this._mouseupHandler);

		console.log(event.target.id);

		var boardId = document.URL.split('id=')[1];
		var itemId = event.target.id;
		var x = event.x - this.offsetX;
		var y = event.y - this.offsetY;

		$.ajax({
			type: 'POST',
			url: 'index.php?page=view&id=' + boardId,
			data: 'id=' + itemId + '&x=' + x + '&y=' + y + '&action=' + 'update',
			success:function(response){
			}
		});

	};

	WhiteboardItem.prototype.editItemHander = function(event) {
		event.preventDefault();
		console.log(event);
	};


	WhiteboardItem.prototype.deleteItemHander = function(event) {
		event.preventDefault();
		console.log(event.path[1].getAttribute('id'));
		this.el.parentNode.removeChild(this.el);
	};

	return WhiteboardItem;
})();