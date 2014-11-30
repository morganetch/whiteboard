module.exports = (function(){

	var WhiteboardItem = require('./WhiteboardItem');

	function WhiteboardApplication(el) {
		this.el = el;
		this.items = this.el.querySelectorAll('.item');

		for(var i = 0; i < this.items.length; i++){
			this.addItem(this.items[i]);
		}
	}

	WhiteboardApplication.prototype.addItem = function(item) {
		var item = new WhiteboardItem(item);
	};

	return WhiteboardApplication;
})();