+function($, window) {
	'use strict';

	var Api = {

	};

	Api.apiUrl = $('meta[name="api-url"]').attr('content');

	Api.getUrl = function() {
		return this.apiUrl;
	}

	Api.request = function(method, parameters) {

		var parameters = parameters || {};

		var url = this.getUrl() + '/' + method;

		var jqxhr = $.ajax({

			"url": url,
			type: 'POST',
			data: parameters
		});

		return jqxhr;
	};

	window.Api = Api;
}(jQuery, window);