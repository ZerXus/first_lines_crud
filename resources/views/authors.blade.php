<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    {{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">--}}
</head>
<style>

</style>
<body>

<h2 style="margin-top: 12px;">Авторы</h2>

<label>
    Фамилия <input type="text" id="last_name" name="last_name">
</label>
<label>
    Имя <input type="text" id="first_name" name="first_name">
</label>
<label>
    Отчество <input type="text" id="middle_name" name="middle_name">
</label>
<button type="button" onclick="createRow()">Save</button>


<table id="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Отчество</th>
        <th>Изменить</th>
        <th>Удалить</th>
        <th>Журналы</th>
    </tr>
    </thead>
    <tbody id="results">
    @foreach($authors as $author)
        <tr id="row_{{$author->id}}">
            <td>{{ $author->id  }}</td>
            <td><input type="text" id="row_{{$author->id}}_lName" value="{{ $author->last_name }}" disabled></td>
            <td><input type="text" id="row_{{$author->id}}_fName" value="{{ $author->first_name }}" disabled></td>
            <td><input type="text" id="row_{{$author->id}}_mName" value="{{ $author->middle_name }}" disabled></td>
            <td>
                <a id="edit_{{ $author->id }}" href="javascript:void(0)" data-id="{{ $author->id }}"
                   onclick="enableEditing(event)">Изменить</a>
                <a id="save_{{ $author->id }}" href="javascript:void(0)" data-id="{{ $author->id }}"
                   onclick="editRow(event)" style="display: none">Сохранить</a>
            </td>
            <td><a href="javascript:void(0)" data-id="{{ $author->id }}"
                   onclick="deleteRow(event)">Удалить</a>
            </td>
            <td><a href="javascript:void(0)" data-id="{{ $author->id }}"
                   onclick="alertJournals(event)">Журналы в алерт!</a>
            </td>
        </tr>
    @endforeach
    </tbody>
    <div class="container">
        @foreach ($authors as $author)
            {{ $author->name }}
        @endforeach
    </div>

    {{ $authors->links() }}

</table>

</body>
<script>
    let url = '/api/author/';

    appendRow = data => {
        $('table tbody').append(`
                        <tr>
<td colspan="6">При обновлении переместится на свое место в табилце</td>
</tr>
                        <tr id="row_${data.id}">
                            <td>${data.id}</td>
                            <td><input type="text" id="row_${data.id}_lName"
                                       value="${data.last_name}" disabled></td>
                            <td><input type="text" id="row_${data.id}_fName"
                                       value="${data.first_name}" disabled></td>
                            <td><input type="text" id="row_${data.id}_mName"
                                       value="${data.middle_name}" disabled></td>
                            <td>
                                <a id="edit_${data.id}" href="javascript:void(0)" data-id="${data.id}"
                                   onclick="enableEditing(event)">Изменить</a>
                                <a id="save_${data.id}" href="javascript:void(0)" data-id="${data.id}"
                                   onclick="editRow(event)" style="display: none">Сохранить</a>
                            </td>
                            <td><a href="javascript:void(0)" data-id="${data.id}"
                                   onclick="deleteRow(event)">Удалить</a>
                            </td>
                            <td><a href="javascript:void(0)" data-id="${data.id}"
                                   onclick="alertJournals(event)">Журналы в алерт!</a>
                            </td>
                        </tr>`);
    }
    toPage = event => {
        let href = $(event.target).data("href");
        $.ajax({
            url: href,
            success: function (response) {
                $('html').empty();
                $('html').append(response);
            }
        })
    }
    alertJournals = event => {
        let id = $(event.target).data("id");
        $.ajax({
            url: '/api/author/' + id + '/journals',
            success: function (response) {
                let data = response.data;
                console.log(data);
                let journals = [];
                data.forEach(j => journals.push(j.title));
                console.log(journals);
                alert(journals.join(',') + '  -  ' + 'всего ' + journals.length + ' шт');
            }
        })
    }
    toggleEdits = (id, isStartedEditing) => {
        if (isStartedEditing) {
            $("#edit_" + id).css('display', 'none');
            $("#save_" + id).css('display', 'block');
            console.log($("#row_" + id + "_fName"));
            $("#row_" + id + "_fName").prop('disabled', function (i, v) {
                return !v;
            });
            $("#row_" + id + "_mName").prop('disabled', function (i, v) {
                return !v;
            });
            $("#row_" + id + "_lName").prop('disabled', function (i, v) {
                return !v;
            });
        } else {
            $("#save_" + id).css('display', 'none');
            $("#edit_" + id).css('display', 'block');

            $("#row_" + id + "_fName").prop('disabled', function (i, v) {
                return !v;
            });
            $("#row_" + id + "_mName").prop('disabled', function (i, v) {
                return !v;
            });
            $("#row_" + id + "_lName").prop('disabled', function (i, v) {
                return !v;
            });
        }
    }

    enableEditing = event => {
        let id = $(event.target).data("id");
        toggleEdits(id, true);
    }

    editRow = event => {
        let id = $(event.target).data("id");
        let _url = url + id;

        let fn = $("#row_" + id + "_fName").val();
        let mn = $("#row_" + id + "_mName").val();
        let ln = $("#row_" + id + "_lName").val();

        $.ajax({
            url: _url,
            type: "PUT",
            data: {
                first_name: fn,
                last_name: ln,
                middle_name: mn
            },
            success: function (response) {
                toggleEdits(id, false);
                alert("Изменения сохранены");
            },
            error: function (error) {
                console.log('Произошла ошибка');
            }
        });
    }

    createRow = () => {
        let _url = url;

        let lname = $('#last_name');
        let fname = $('#first_name');
        let mname = $('#middle_name');

        $.ajax({
            url: _url,
            type: "POST",
            data: {
                first_name: fname.val(),
                middle_name: mname.val(),
                last_name: lname.val(),
            },
            success: function (response) {
                console.log(response);
                appendRow(response.data);

                lname.val('');
                fname.val('');
                mname.val('');

            }
        });
    }

    deleteRow = event => {
        var id = $(event.target).data("id");
        let _url = url + id;
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: _url,
            type: 'DELETE',
            data: {
                _token: _token
            },
            success: function (response) {
                $("#row_" + id).remove();
            }
        });
    }

</script>
</html>
