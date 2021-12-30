$(document).ready(function() {
    $('#postComment').click(function() {
        var execute = true;
        var data = {};
        var inputs;
        var type = 'group_comment';

        $('#text-input').each(function() {
            inputs = $(this).find(':input');
            console.log(inputs);
            for (var i = 0; i < inputs.length; i++) {
                data[inputs[i].id] = inputs[i].value;

                if (inputs[i].value == '') {
                    execute = false;
                }
            }
        });

        console.log(execute);
        if (execute) {
            inputs[0].value = '';

            $.ajax({
                url: 'index.php?'+ type +'/post',
                type: 'POST',
                data: data,
                success: function(data, textStatus, jqXHR) {
                    var data = JSON.parse(data);
                    console.log(data);
                    
                    var card =  '<div class="card card-flat card-wide">';
                        card += '<div class="row">';
                        card += '<img src="images/users/'+ data['user_image'] +'" alt="">';
                        card += '<div class="card-details">';
                        card += '<p class="card-heading">' + data['user_name'] + ' | ' + data['month'] + ' ' + data['day'] + ' @ ' +data['hour']+ ':' +data['minutes'] + ' ' +data['meridiem'] + '</p>';
                        card += '<p>' +data['text']+ '</p>';
                        card += '</div></div></div>';

                    $('#comments').prepend(card);
                }
            });
        }
    });
});