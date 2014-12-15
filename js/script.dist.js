!function e(t,i,n){function o(r,a){if(!i[r]){if(!t[r]){var d="function"==typeof require&&require;if(!a&&d)return d(r,!0);if(s)return s(r,!0);var l=new Error("Cannot find module '"+r+"'");throw l.code="MODULE_NOT_FOUND",l}var u=i[r]={exports:{}};t[r][0].call(u.exports,function(e){var i=t[r][1][e];return o(i?i:e)},u,u.exports,e,t,i,n)}return i[r].exports}for(var s="function"==typeof require&&require,r=0;r<n.length;r++)o(n[r]);return o}({1:[function(e){!function(){function t(){var t=document.URL.split("?page=")[1].split("&")[0];switch(t){case"login":console.log("loginpage");break;case"register":console.log("registerpage");break;case"view":var i=e("./classes/WhiteboardApplication");new i(document.querySelector(".view"));break;case"settings":var n=e("./classes/WhiteboardSettings");new n(document.querySelector(".settingspage"))}}t()}()},{"./classes/WhiteboardApplication":2,"./classes/WhiteboardSettings":5}],2:[function(e,t){t.exports=function(){function t(e){this.el=e,this.items=this.el.querySelectorAll(".item"),this.whiteboardItems=[],this.$itemsList=$(e).find(".holder"),this.WhiteboardForm=new n(this.el.querySelector(".buttons"));for(var t=0;t<this.items.length;t++)this.addItemPreparer(this.items[t]);$(this.items).remove()}var i=e("./WhiteboardItem"),n=e("./WhiteboardForm");return t.prototype.addItemPreparer=function(e){var t,i=e.querySelector("section");switch(parseInt(i.id)){case 1:t=i.querySelector('img[src^="uploads"]').getAttribute("src");break;case 2:t=i.querySelector("video").getAttribute("src");break;case 3:t=i.querySelector("p").innerText}var n={left:e.style.left,top:e.style.top,z:e.style.zIndex,id:e.id,type:i.id,title:i.querySelector("h1").innerText,description:i.querySelector(".desc").innerText,content:t,"new":!1};this.addItemHandler(n)},t.prototype.makeNewItem=function(e){e.preventDefault();var t;t=e.target.getAttribute("alt")?e.target.parentNode.getAttribute("href"):e.target.getAttribute("href")},t.prototype.addItemHandler=function(e){var t=i.createItem(e);bean.on(t,"delete",this.deleteItemHandler.bind(this)),this.whiteboardItems.push(e),this.$itemsList.append(t.$el)},t.prototype.deleteItemHandler=function(e){this.whiteboardItems.splice(this.whiteboardItems.indexOf(e),1),console.log("spliced")},t.prototype.updateItems=function(){},t}()},{"./WhiteboardForm":3,"./WhiteboardItem":4}],3:[function(e,t){t.exports=function(){function t(e){this.el=e;var t=$(this.el).find('input[id="image-upload"]');t.on("change",this.imageValidation.bind(this));var i=$(this.el).find('input[id="video-upload"]');i.on("change",this.videoValidation.bind(this))}e("./WhiteboardItem");return t.prototype.imageValidation=function(){if(window.File&&window.FileReader&&window.FileList&&window.Blob){var e=this.el.querySelector('input[id="image-upload"]').files[0];if(0===e.type.indexOf("image")){var t=$(this.el).find('input[value="image"]');t.click()}}},t.prototype.videoValidation=function(e){e.preventDefault();var t=this.el.querySelector('input[id="video-upload"]').files[0];if(0===t.type.indexOf("video")){var i=$(this.el).find('input[value="video"]');i.click()}},t}()},{"./WhiteboardItem":4}],4:[function(e,t){t.exports=function(){function e(e,t){this.$el=e,this.$editButton=this.$el.find('img[alt="settings"]'),this.$dragBar=this.$el.find("div.border"),this.$section=this.$el.find("section"),this.data=t,this.$editButton.on("click",this.editItemHandler.bind(this)),this.$dragBar.on("mousedown",this.mousedownHandler.bind(this))}return e.prototype.mousedownHandler=function(e){e.preventDefault(),this.$el.css("zIndex",this.getHighestZ),this.offsetX=e.offsetX,this.offsetY=e.offsetY,this._mousemoveHandler=this.mousemoveHandler.bind(this),this._mouseupHandler=this.mouseupHandler.bind(this),window.addEventListener("mousemove",this._mousemoveHandler),window.addEventListener("mouseup",this._mouseupHandler)},e.prototype.mousemoveHandler=function(e){this.$el.css("left",e.pageX-this.offsetX+"px"),this.$el.css("top",e.pageY-this.offsetY+"px"),this.$el.css("top").split("px")[0]<40&&this.$el.css("top","40px")},e.prototype.mouseupHandler=function(){var e=document.URL.split("id=")[1];console.log(this.data.id),$.post("index.php?page=save",{id:this.data.id,x:this.$el.css("left"),y:this.$el.css("top"),z:this.$el.css("zIndex"),boardId:e}).done(function(e){console.log(e)}),window.removeEventListener("mousemove",this._mousemoveHandler),window.removeEventListener("mouseup",this._mouseupHandler)},e.prototype.editItemHandler=function(e){e.preventDefault(),this.$section.addClass("hidden");var t=Handlebars.compile($("#edit-"+this.data.type+"-template").text());this.$el.append($(t(this.data))),this.$submit=this.$el.find('input[type="submit"]'),this.$submit.on("submit",this.formHandler.bind(this)),this.$delete=this.$el.find("a"),this.$delete.on("click",this.deleteClickHandler.bind(this))},e.prototype.formHandler=function(){switch(this.form=this.el.querySelector("form"),console.log(this.type),this.type){case 1:break;case 2:break;case 3:$.post("index.php?page=edit",{id:this.data.id,title:this.form.querySelector('input[name="title"]').value,content:this.form.querySelector('textarea[name="content"]').value,desc:this.form.querySelector('textarea[name="desc"]').value,boardId:boardId}).done(function(){})}},e.prototype.deleteClickHandler=function(e){e.preventDefault(),this.$el.remove(),bean.fire(this,"delete",this),console.log("clickhandler"),$.post("index.php?page=deleteitem",{id:this.data.id}).done(function(){})},e.createItem=function(t){var i=$("#"+t.type+"-template").html(),n=Handlebars.compile(i),o=n(t);return new e($(o),t)},e.prototype.getHighestZ=function(){var e=[],t=$(".holder").find("article");t.each(function(t,i){e.push($(i).css("zIndex"))});var i=Math.max.apply(null,e);return i+1},e}()},{}],5:[function(e,t){t.exports=function(){function e(e){this.el=e,console.log("settings"),this.searchForm=this.el.querySelector("form.search"),this.searchInput=this.searchForm.querySelector('input[type="search"]'),this.searchInput.addEventListener("input",this.doSearch.bind(this))}return e.prototype.doSearch=function(e){e.preventDefault();var t=new XMLHttpRequest;t.onload=function(){var e=document.createElement("div");e.innerHTML=t.responseText,console.log(e.innerHTML);var i=e.querySelector(".result"),n=document.querySelector(".result");n.parentNode.replaceChild(i,n)},t.open("get",this.searchForm.getAttribute("action")+"&q="+this.searchInput.value,!0),t.setRequestHeader("X_REQUESTED_WITH","xmlhttprequest"),t.send()},e}()},{}]},{},[1]);
//# sourceMappingURL=../maps/script.dist.js.map