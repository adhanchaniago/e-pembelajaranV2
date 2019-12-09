var table;

$(document).ready(function () {

    ajaxcsrf();

    table = $("#detail_hasil").DataTable({
        initComplete: function () {
            var api = this.api();
            $('#detail_hasil_filter input')
                .off('.DT')
                .on('keyup.DT', function (e) {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "loading..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            "url": base_url + "ujian/nilai_ujian/" + id,
            "type": "POST",
        },
        columns: [{
                "data": "id_ujian",
                "orderable": false,
                "searchable": false
            },
            {
                "data": 'nama'
            },
            {
                "data": 'nama_kelas'
            },
            {
                "data": 'nilai_uts'
            },
            {
                "data": 'nilai_uas'
            }
        ],
        columnDefs: [{
                targets: 3,
                data: "nilai_uts",
                render: function (data, type, row, meta) {
                    if (data !== null) {
                        return `
                    <div>
                    <input name="uts[]" class="form-control" value="${data}" type="number">
                    </div>
                          `;
                    } else {
                        return `
                    <div>
                    <input name="uts[]" class="form-control" type="number">
                    </div>
                          `;
                    }
                }
            },
            {
                targets: 4,
                data: "nilai_uas",
                render: function (data, type, row, meta) {
                    if (data !== null) {
                        return `
                    <div>
                    <input name="uas[]" class="form-control" value="${data}" type="number">
                    </div>
                          `;
                    } else {
                        return `
                    <div>
                    <input name="uas[]" class="form-control" type="number">
                    </div>
                          `;
                    }
                }
            },
            {
                targets: 5,
                data: "id_ujian",
                render: function (data, type, row, meta) {

                    return `
                    <div>
                    <input name="id_ujian[]" class="form-control" value="${data}" type="hidden">
                    </div>
                          `;

                }
            },
            {
                targets: 6,
                data: "id_siswa",
                render: function (data, type, row, meta) {

                    return `
                    <div>
                    <input name="id_siswa[]" class="form-control" value="${data}" type="hidden">
                    </div>
                          `;

                }
            }
        ],
        order: [
            [1, 'asc']
        ],
        rowId: function (a) {
            return a;
        },
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(0)', row).html(index);
        }
    });


    $("#ujian").on("submit", function (e) {

        e.preventDefault();
        e.stopImmediatePropagation();

        $.ajax({
            url: base_url + 'ujian/save',
            data: $(this).serialize(),
            type: "POST",
            success: function (respon) {
                if (respon.status) {
                    Swal({
                        title: "Berhasil",
                        text: " Nilai berhasil ditambahkan",
                        type: "success"
                    });
                } else {
                    Swal({
                        title: "Gagal",
                        text: "Nilai gagal ditambahkan",
                        type: "error"
                    });
                }
                reload_ajax();
            },
            error: function () {
                Swal({
                    title: "Gagal",
                    text: "Ada data yang sedang digunakan",
                    type: "error"
                });
            }
        });
    });
});