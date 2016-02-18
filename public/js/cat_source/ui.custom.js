/*
	Component: ui.custom
 */
$.extend(UI, {
  getCustomData: function(){
    $.ajax({
      url: 'https://api.github.com/repos/matecat/matecat/issues?callback=?',
      dataType: 'jsonp',
      success: function(d){
        UI.renderCustom(d);
      }
    });
  },
  renderCustom: function(d) {
    setInterval(UI.setClock, 1000);

    $('.custom .left .issues').html('');

    $.each(d.data, function(k, v) {
      var div = document.createElement('div');
      div.setAttribute('class', 'item');
      var a = document.createElement('a');
      a.setAttribute('href', v.html_url);
      a.setAttribute('target', '_blank');
      var tx = document.createTextNode(v.title);
      a.appendChild(tx);
      div.appendChild(a);

      $('.custom .left .issues').append(div);
    });
	},
  setClock: function() {
    var now = new Date();
    var time = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2) + ':' + ('0' + now.getSeconds()).slice(-2);
    $('.custom .right').html(time);
  }
});
