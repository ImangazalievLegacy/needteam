$(document).ready(function(){

    $('#poster-upload-dialog').change(function(){
        var files = $('#poster-upload-dialog')[0].files;

        var jqxhr = Api.uploadImage(files);

        console.log(jqxhr);

        if (jqxhr === false) {
            alert('Ошибка при загрузке файлов');
            return;
        }

        jqxhr
        .done(function(data){
            console.log(data);

            var path = data.response.files['file1'];

            // preview
            var file = $('#poster-upload-dialog')[0].files[0];
        
            var reader = new FileReader();

            reader.readAsDataURL(file);

            reader.onload = function (e) {
                var src = e.target.result;

                var source   = $("#thumbnail-template").html();
                var template = Handlebars.compile(source);
                var context  = {"src": src, "path": path, "name": 'poster'};
                var html     = template(context);

                $('#poster').html(html);
            }
        })

        .fail(function(xhr) {
            alert('Ошибка загрузки файлов');

            console.log(xhr.responseText);
        });
    });
});