(function() {
	tinymce.create('tinymce.plugins.buttonPlugin', {
		init : function(ed, url) {
			var plugin_url = url.substr(0,url.indexOf('/assets'));
			// Register commands
			ed.addCommand('mcebutton_add_curatedfeed', function() {
				ed.windowManager.open({
					file : plugin_url + '/shortcodes/setup-shortcode.php?type=curatedfeed', // file that contains HTML for our modal window
					width : 400 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 650 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : plugin_url
				});
			});

			ed.addCommand('mcebutton_add_feed', function() {
				ed.windowManager.open({
					file : plugin_url + '/shortcodes/setup-shortcode.php?type=feed', // file that contains HTML for our modal window
					width : 400 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 650 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : plugin_url
				});
			});
 
			ed.addCommand('mcebutton_add_savedsearch', function() {
				ed.windowManager.open({
					file : plugin_url + '/shortcodes/setup-shortcode.php?type=savedsearch', // file that contains HTML for our modal window
					width : 400 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 650 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : plugin_url
				});
			});

			ed.addCommand('mcebutton_add_topic', function() {
				ed.windowManager.open({
					file : plugin_url + '/shortcodes/setup-shortcode.php?type=topic', // file that contains HTML for our modal window
					width : 400 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 650 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : plugin_url
				});
			});

			ed.addCommand('mcebutton_add_tweet', function() {
				ed.windowManager.open({
					file : plugin_url + '/shortcodes/setup-shortcode.php?type=tweet', // file that contains HTML for our modal window
					width : 400 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 430 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : plugin_url
				});
			});

			// Register buttons
			ed.addButton('ptcuratedfeed_button', {title : 'Add Curated Mix', cmd : 'mcebutton_add_curatedfeed', style: 'width: 61px;background:url("' + plugin_url + '/assets/img/shortcode-icons.png") no-repeat -168px -21px', minWidth: 100, image: plugin_url + '/assets/img/empty.gif' });
			ed.addButton('ptfeed_button', {title : 'Add Mix', cmd : 'mcebutton_add_feed', style: 'width: 54px;background:url("' + plugin_url + '/assets/img/shortcode-icons.png") no-repeat 0px -21px', image: plugin_url + '/assets/img/empty.gif' });
			ed.addButton('ptsavedsearch_button', {title : 'Add Search', cmd : 'mcebutton_add_savedsearch', style: 'width: 56px;background:url("' + plugin_url + '/assets/img/shortcode-icons.png") no-repeat -110px -21px', image: plugin_url + '/assets/img/empty.gif' });
			ed.addButton('pttopics_button', {title : 'Add Topic', cmd : 'mcebutton_add_topic', style: 'width: 51px;background:url("' + plugin_url + '/assets/img/shortcode-icons.png") no-repeat -56px -21px', image: plugin_url + '/assets/img/empty.gif' });
			ed.addButton('pttweets_button', {title : 'Add Tweet', cmd : 'mcebutton_add_tweet', style: 'width: 61px;background:url("' + plugin_url + '/assets/img/shortcode-icons.png") no-repeat -231px -21px', image: plugin_url + '/assets/img/empty.gif' });
		},
 
		getInfo : function() {
			return {
				longname : 'Insert Button',
				author : 'Irina Leontovich',
				authorurl : 'http://publishthis.com',
				infourl : 'http://publishthis.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
 
	// Register plugin
	// first parameter is the button ID and must match ID elsewhere
	// second parameter must match the first parameter of the tinymce.create() function above
	tinymce.PluginManager.add('ptfeed_button', tinymce.plugins.buttonPlugin);
	tinymce.PluginManager.add('ptsavedsearch_button', tinymce.plugins.buttonPlugin);
	tinymce.PluginManager.add('pttopics_button', tinymce.plugins.buttonPlugin);
	tinymce.PluginManager.add('pttweets_button', tinymce.plugins.buttonPlugin);
	tinymce.PluginManager.add('ptcuratedfeed_button', tinymce.plugins.buttonPlugin);
})();