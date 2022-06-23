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

<h2 style="margin-top: 12px;">Журналы</h2>

<label>
    Название <input type="text" id="title">
</label>
<label>
    Описание <input type="text" id="shortdesc">
</label>
<label>
    Авторы(id через ",") <input type="text" id="authors">
</label>
<label>
    Дата строкой гггг-мм-дд <input type="text" id="date">
</label>
<button type="button" onclick="createRow()">Save</button>


<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Описание</th>
        <th>Авторы</th>
        <th>Дата выпуска</th>
        <th>Изменить</th>
        <th>Удалить</th>
    </tr>
    </thead>
    <tbody>
    @foreach($journals as $journal)
        <tr id="row_{{$journal->id}}">
            <td>{{ $journal->id  }}</td>
            <td><input type="text" id="row_{{$journal->id}}_title" value="{{ $journal->title }}" disabled></td>
            <td><input type="text" id="row_{{$journal->id}}_shortdesc" value="{{ $journal->short_description }}" disabled></td>
            <td><input type="text" id="row_{{$journal->id}}_authors" value="{{ $journal->authors }}" disabled></td>
            <td><input type="text" id="row_{{$journal->id}}_date" value="{{ $journal->release_date }}" disabled></td>
            <td>
                <a id="edit_{{ $journal->id }}" href="javascript:void(0)" data-id="{{ $journal->id }}"
                   onclick="enableEditing(event)">Изменить</a>
                <a id="save_{{ $journal->id }}" href="javascript:void(0)" data-id="{{ $journal->id }}"
                   onclick="editRow(event)" style="display: none">Сохранить</a>
            </td>
            <td><a href="javascript:void(0)" data-id="{{ $journal->id }}"
                   onclick="deleteRow(event)">Удалить</a>
            </td>
        </tr>
    @endforeach
    </tbody>
    <div class="container">
        @foreach ($journals as $journal)
            {{ $journal->name }}
        @endforeach
    </div>

    {{ $journals->links() }}

</table>

</body>
<script>
    let url = '/api/journal/';

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

    toggleEdits = (id, isStartedEditing) => {
        if (isStartedEditing) {
            $("#edit_" + id).css('display', 'none');
            $("#save_" + id).css('display', 'block');

            $("#row_" + id + "_title").prop('disabled', (i,v) => !v);
            $("#row_" + id + "_shortdesc").prop('disabled', (i,v) => !v);
            $("#row_" + id + "_authors").prop('disabled', (i,v) => !v);
            $("#row_" + id + "_date").prop('disabled', (i,v) => !v);
        } else {
            $("#save_" + id).css('display', 'none');
            $("#edit_" + id).css('display', 'block');

            $("#row_" + id + "_title").prop('disabled', (i,v) => !v);
            $("#row_" + id + "_shortdesc").prop('disabled', (i,v) => !v);
            $("#row_" + id + "_authors").prop('disabled', (i,v) => !v);
            $("#row_" + id + "_date").prop('disabled', (i,v) => !v);
        }
    }

    enableEditing = event => {
        let id = $(event.target).data("id");
        toggleEdits(id, true);
    }

    editRow = event => {
        let id = $(event.target).data("id");
        let _url = url + id;

        let title = $("#row_" + id + "_title").val();
        let shortdesc = $("#row_" + id + "_shortdesc").val();
        let authors = $("#row_" + id + "_authors").val();
        let date = $("#row_" + id + "_date").val();

        $.ajax({
            url: _url,
            type: "PUT",
            data: {
                title: title,
                short_description: shortdesc,
                authors: authors,
                release_date: date
            },
            success: function (response) {
                toggleEdits(id, false);
                alert("Изменения сохранены");
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    createRow = () => {
        let _url = url;

        let title = $("#title");
        let shortdesc = $("#shortdesc");
        let authors = $("#authors");
        let date = $("#date");
        $.ajax({
            url: _url,
            type: "POST",
            data: {
                title: title.val(),
                short_description: shortdesc.val(),
                authors: authors.val(),
                release_date: date.val()
            },
            success: function (response) {
                console.log(response);
                $('table tbody').append(`
                              <tr>
                            <td colspan="7">При обновлении переместится на свое место в табилце</td>
                            </tr>
                        <tr id="row_${response.data.id}">
                            <td>${response.data.id}</td>
                            <td><input type="text" id="row_${response.data.id}_title" value="${response.data.title}" disabled></td>
                            <td><input type="text" id="row_${response.data.id}_shortdesc" value="${response.data.description}" disabled></td>
                            <td><input type="text" id="row_${response.data.id}_authors" value="${response.data.authors}" disabled></td>
                            <td><input type="text" id="row_${response.data.id}_date" value="${response.data.release_date}" disabled></td>
                            <td>
                                <a id="edit_${response.data.id}" href="javascript:void(0)" data-id="${response.data.id}"
                                   onclick="enableEditing(event)">Изменить</a>
                                <a id="save_${response.data.id}" href="javascript:void(0)" data-id="${response.data.id}"
                                   onclick="editRow(event)" style="display: none">Сохранить</a>
                            </td>
                            <td>
                                <a href="javascript:void(0)" data-id="${response.data.id}"
                                   onclick="deleteRow(event)">Удалить</a>
                            </td>
                        </tr>
                        `);

                title.val('');
                shortdesc.val('');
                authors.val('');
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    deleteRow = event => {
        var id = $(event).data("id");
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
