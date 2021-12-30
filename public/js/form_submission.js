$(document).ready(function() {
    var initialValue = '';
    var type = $('#type').val();
    console.log(type);

    function edit() {
        $('.edit').click(function() {
            $(this).addClass('editMode');
        });
    
        $('.edit').focus(function() {
            initialValue = $(this).text();
        });
    
        $('.edit').focusout(function() {
            $(this).removeClass('editMode');
            var value = $(this).text();
            var field = $(this).attr('id').split('-')[0];
            var id = $(this).attr('id').split('-')[1];
    
            console.log(field);
    
            if (value != '') {
    
                $.ajax({
                    url: 'index.php?'+ type +'/edit',
                    type: 'POST',
                    data: {
                        id: id,
                        field: field,
                        value: value
                    }
                });
    
            } else {
                $(this).text(initialValue);
            }
        });
    }

    function del() {
        $('.deleteBtn').click(function() {
            var id = $(this).attr('id').split('-')[1];
            var row = $(this).parent().parent();
            console.log(id);
            $.ajax({
                url: 'index.php?'+ type +'/delete',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(data, textStatus, jqXHR) {
                    console.log(data);
                    row.remove();

                    var count = document.getElementById('count');
                    count.innerHTML = parseInt(count.innerHTML) - 1;
                }
            });
        });
    }

    edit();
    del();

    $('#create').click(function() {
        var data = {};
        var inputs;
        var execute = true;
        $('#inputs').each(function() {
            inputs = $(this).find(':input');
            
            console.log(inputs);
            for (var i = 1; i < inputs.length; i++) {
                console.log(inputs[i].value);
                if (inputs[i].value == "") {
                    inputs[i].classList.add('is-invalid');
                    execute = false;
                } else {
                    inputs[i].classList.remove('is-invalid');
                }
                data[inputs[i].id] = inputs[i].value;
            }
            
        });
        console.log(data);
        if (execute) {
            for (var i = 1; i < inputs.length; i++) {
                inputs[i].value = "";
            }
            $.ajax({
                url: 'index.php?'+ type +'/create',
                type: 'POST',
                data: data,
                success: function(data, textStatus, jqXHR) {
                    console.log(data);
                    var type;
                    var parsedData = JSON.parse(data);
                    var lastItem = parsedData[parsedData.length - 1];
                    var tableHead = $('#tableHead');
                    var tableBody = $('#tableBody');
                    console.log(tableHead[0].children[0].children);
                    var row = '';
                    var tableRow = tableHead[0].children[0].children;

                    row += '<tr>';
                    row += '<td scope="row">' + lastItem['id'] +'</td>';
                    row += '<td scope="row"><button id="deleteBtn-'+ lastItem['id'] +'" class="deleteBtn btn btn-danger btn-sm">Delete</button></td>';
                    for (var i = 2; i < tableRow.length; i++) {
                        if (tableRow[i].className == 'editable') {
                            type = tableRow[i].firstChild.id.split('-')[0];
                            row += '<td><div contenteditable="true" class="edit" id="'+ type +'-'+ lastItem['id'] +'">'+ lastItem[type] +'</div></td>';
                        } else {
                            type = tableRow[i].firstChild.id;
                            row += '<td>'+ lastItem[type] +'</td>';
                        }
                    }
                    

                    tableBody.append(row);
                    console.log(parsedData[parsedData.length - 1]);

                    var count = document.getElementById('count');
                    count.innerHTML = parseInt(count.innerHTML) + 1;

                    edit();
                    del();
                }
            });
        } else {
            console.log(execute);
        }
    });

    $('#createSchool').click(function() {
        var data = {};
        var inputs;
        var execute = true;
        $('#inputs').each(function() {
            inputs = $(this).find(':input');
            
            console.log(inputs);
            for (var i = 1; i < inputs.length; i++) {
                console.log(inputs[i].value);
                if (inputs[i].value == "") {
                    inputs[i].classList.add('is-invalid');
                    execute = false;
                } else {
                    inputs[i].classList.remove('is-invalid');
                }
                data[inputs[i].id] = inputs[i].value;
            }
            
        });
        console.log(data);
        if (execute) {
            for (var i = 1; i < inputs.length; i++) {
                inputs[i].value = "";
            }
            $.ajax({
                url: 'index.php?'+ type +'/create',
                type: 'POST',
                data: data,
                success: function(data, textStatus, jqXHR) {
                    console.log(data);
                    var type;
                    var parsedData = JSON.parse(data);
                    var lastItem = parsedData[parsedData.length - 1];
                    var tableHead = $('#tableHead');
                    var tableBody = $('#tableBody');
                    console.log(tableHead[0].children[0].children);
                    var row = '';
                    var tableRow = tableHead[0].children[0].children;

                    row += '<tr>';
                    row += '<td scope="row">' + lastItem['id'] +'</td>';
                    row += '<td scope="row"><button id="deleteBtn-'+ lastItem['id'] +'" class="deleteBtn btn btn-danger btn-sm">Delete</button></td>';
                    for (var i = 2; i < tableRow.length; i++) {
                        if (tableRow[i].className == 'editable') {
                            type = tableRow[i].firstChild.id.split('-')[0];
                            row += '<td><div contenteditable="true" class="edit" id="'+ type +'-'+ lastItem['id'] +'">'+ lastItem[type] +'</div></td>';
                        } else {
                            type = tableRow[i].firstChild.id;
                            if (type == 'status') {
                                
                                if (lastItem[type] == 1) {
                                    console.log('This is the status of ' + lastItem[type]);
                                    row += '<td><div id="status"><button type="button" class="btn btn-secondary btn-sm" disabled>Pending School Submittal</button></div></td>';
                                } else if (lastItem[type] == 2) {
                                    row += '<td><div id="status"><button class="btn btn-primary btn-sm" href="">Click to Verify</button></div></td>';
                                } else if (lastItem[type] == 3) {
                                    row += '<td><div id="status"><button class="btn btn-success btn-sm" href="#" role="button"><i class="fa fa-check-circle" aria-hidden="true"></i> View Submittal</button></div></td>';
                                }
                            } else {
                                row += '<td>'+ lastItem[type] +'</td>';
                            }
                            
                        }
                    }
                    

                    tableBody.append(row);
                    console.log(parsedData[parsedData.length - 1]);

                    var count = document.getElementById('count');
                    count.innerHTML = parseInt(count.innerHTML) + 1;

                    edit();
                    del();
                }
            });
        } else {
            console.log(execute);
        }
    });

    $('#upload_form').submit(function(event) {
        var execute = true;
        event.preventDefault();
        var formData = new FormData($('#upload_form')[0]);
        formData.append('icon', $('input[type=file]')[0].files[0]);
        var type = 'achievement';

        var inputs = $(this).find(':input').prevObject[0];
        for (var i = 1; i < inputs.length; i++) {
            if (inputs[i].value == "") {
                execute = false;
                inputs[i].classList.add('is-invalid');
            } else {
                inputs[i].classList.remove('is-invalid');
            }
            

            if (inputs[i].nextElementSibling) {
                console.log(inputs[i].nextElementSibling);
                inputs[i].nextElementSibling.innerHTML = 'Icon Image';
            }
        }

        if (execute) {
            for (var i = 1; i < inputs.length; i++) {
                inputs[i].value = "";
            }
            $.ajax({
                type: 'POST',
                url: 'index.php?achievement/create',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                    console.log(data);
                    var type;
                    var parsedData = JSON.parse(data);
                    var lastItem = parsedData[parsedData.length - 1];
                    var tableHead = $('#tableHead');
                    var tableBody = $('#tableBody');
                    var row = '';
                    var tableRow = tableHead[0].children[0].children;

                    row += '<tr>';
                    row += '<td scope="row">' + lastItem['id'] +'</td>';
                    row += '<td scope="row"><button id="deleteBtn-'+ lastItem['id'] +'" class="deleteBtn btn btn-danger btn-sm">Delete</button></td>';
                    for (var i = 2; i < tableRow.length; i++) {
                        if (tableRow[i].className == 'editable') {
                            type = tableRow[i].firstChild.id.split('-')[0];
                            row += '<td><div contenteditable="true" class="edit" id="'+ type +'-'+ lastItem['id'] +'">'+ lastItem[type] +'</div></td>';
                        } else {
                            type = tableRow[i].firstChild.id;
                            row += '<td>'+ lastItem[type] +'</td>';
                        }
                    }
                    

                    tableBody.append(row);
                    console.log(parsedData[parsedData.length - 1]);

                    edit();
                    del();
                }
            });
        } else {
            console.log(execute);
        }
    });
});



