/**
 * Created by JimmDiGriz on 06.08.2017.
 */

$(document).ready(function () {
    $('.complete-task').on('click', function() {
        var self = $(this);
        
        var id = self.data('task');
        
        $.post('/api/complete/', {
            taskId: id,
        }, function (response) {
            self.parent().html('Да');
        });
    });

    var preview = $('#task-preview');
    var previewName = $('#preview-name');
    var previewEmail = $('#preview-email');
    var previewText = $('#preview-text');
    var previewImage = $('#preview-image');

    $('#photoInput').on('change', function(event) {
        var target = event.target || window.event.srcElement,
            files = target.files;
        
        if (FileReader && files && files.length) {
            var fr = new FileReader();
            fr.onload = function () {
                previewImage.attr('src', fr.result);
            };
            
            fr.readAsDataURL(files[0]);
        }
    });
    
    $('#run-preview').on('click', function() {
        previewName.html($('#userName').val());
        previewEmail.html($('#emailInput').val());
        previewText.html($('#textInput').val());
        
        preview.removeClass('hidden');
    });
});