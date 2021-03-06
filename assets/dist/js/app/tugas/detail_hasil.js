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
            "url": base_url + "hasiltugas/NilaiMhs/" + id,
            "type": "POST",
        },
        columns: [{
                "data": "id",
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
                "data": 'nama_jurusan'
            },
            {
                "data": 'nilai'
            },
        ],
        columnDefs: [{
            targets: 5,
            data: "id",
            render: function (data, type, row, meta) {
                if (jenis_soal === 'essay') {
                    return `
                          <div class="text-center">
                              <a class="btn btn-xs bg-blue" href="${base_url}hasiltugas/essay/${data}" >
                                  <i class="fa fa-search"></i> Lihat Jawaban
                              </a>
                          </div>
                          `;
                } else {
                    return null
                }
            }
        }],
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
});