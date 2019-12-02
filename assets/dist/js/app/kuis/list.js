var table;

$(document).ready(function () {

    ajaxcsrf();

    table = $("#kuis").DataTable({
        initComplete: function () {
            var api = this.api();
            $('#kuis_filter input')
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
            "url": base_url + "kuis/list_json",
            "type": "POST",
        },
        columns: [{
                "data": "id_kuis",
                "orderable": false,
                "searchable": false
            },
            {
                "data": 'nama_kuis'
            },
            {
                "data": 'nama_mapel'
            },
            {
                "data": 'nama_topik'
            },
            {
                "data": 'nama_guru'
            },
            {
                "data": 'jumlah_soal'
            },
            {
                "data": 'waktu'
            },
            {
                "searchable": false,
                "orderable": false
            }
        ],
        columnDefs: [{
            "targets": 7,
            "data": {
                "id_kuis": "id_kuis",
                "ada": "ada"
            },
            "render": function (data, type, row, meta) {
                var btn;
                if (data.ada > 0) {
                    btn = `
								<a class="btn btn-xs btn-success" href="${base_url}hasilkuis/cetak/${data.id_kuis}" target="_blank">
									<i class="fa fa-print"></i> Cetak Hasil
								</a>`;
                } else {
                    btn = `<a class="btn btn-xs btn-primary" href="${base_url}kuis/token/${data.id_kuis}">
								<i class="fa fa-pencil"></i> Ikut Kuis
							</a>`;
                }
                return `<div class="text-center">
									${btn}
								</div>`;
            }
        }, ],
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