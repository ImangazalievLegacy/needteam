+function($, Api) {
    'use strict';

    var Uploader = function () {

    }

    Uploader.maxFileCount = 5;

    Uploader.upload = function(method, files, before, progress) {

        var before   = before   || function() {};

        var progress = progress || function() {
            return $.ajaxSettings.xhr();
        };

        console.log('Количество файлов: ' + files.length);

        if (files.length == 0) {
            return false;
        }

        var formData = new FormData();
        var filesNum = files.length > this.maxFileCount ? this.maxFileCount : files.length;

        for (var i = 1; i <= filesNum; i++) {
            formData.append('file'+i, files.item(i-1));
        };

        var apiUrl = Api.getUrl();

        console.log('URL: ', apiUrl+'/'+method);

        var jqxhr = $.ajax({
            url: apiUrl+'/'+method,
            type: 'POST',
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            data: formData,
            cache: false,

            beforeSend: before,
            xhr: progress
        });

        return jqxhr;
    }

    Uploader.uploadImage = function(files, before, progress) {
        return Uploader.upload('files/upload/image', files, before, progress);
    }

    Api.uploadImage = Uploader.uploadImage;
}(jQuery, Api);