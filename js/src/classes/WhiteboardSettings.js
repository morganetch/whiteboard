module.exports = (function(){

	function WhiteboardSettings(el) {
		this.el = el;

		this.searchForm = this.el.querySelector('form.search');
		this.searchInput = this.searchForm.querySelector('input[type="search"]');

		this.searchInput.addEventListener('input', this.doSearch.bind(this));

	}

	WhiteboardSettings.prototype.doSearch = function(event) {
		event.preventDefault();
		var req = new XMLHttpRequest();
		req.onload = function() {
			var result = document.createElement('div');
			result.innerHTML = req.responseText;
			var updatedResultDiv = result.querySelector('.result');
			
			var resultDiv = document.querySelector('.result');
			resultDiv.parentNode.replaceChild(updatedResultDiv, resultDiv);
		};
		req.open('get', this.searchForm.getAttribute('action') + '&q=' + this.searchInput.value, true);
		req.setRequestHeader('X_REQUESTED_WITH', 'xmlhttprequest');
		req.send();
	};

	return WhiteboardSettings;
})();