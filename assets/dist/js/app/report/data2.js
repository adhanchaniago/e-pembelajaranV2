var table;

$(document).ready(function () {
  ajaxcsrf();

  table = $("#report").DataTable({
    "scrollX": true,
    initComplete: function () {
      var api = this.api();
      $("#report_filter input")
        .off(".DT")
        .on("keyup.DT", function (e) {
          api.search(this.value).draw();
        });
    },
    dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [{
        extend: "copy",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]
        }
      },
      {
        extend: "print",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]
        }
      },
      {
        extend: "excel",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]
        }
      },
      {
        extend: "pdf",
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]
        }
      }
    ],
    oLanguage: {
      sProcessing: "loading..."
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: url,
      type: "POST"
    },
    columns: [{
        data: "id_siswa",
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      {
        data: "nama"
      },
      {
        data: "tugas0",
        "className": "text-center"
      },
      {
        data: "ujian0",
        "className": "text-center"
      },
      {
        data: "tugas1",
        "className": "text-center"
      },
      {
        data: "ujian1",
        "className": "text-center"
      },
      {
        data: "tugas2",
        "className": "text-center"
      },
      {
        data: "ujian2",
        "className": "text-center"
      },
      {
        data: "tugas3",
        "className": "text-center"
      },
      {
        data: "ujian3",
        "className": "text-center"
      },
      {
        data: "tugas4",
        "className": "text-center"
      },
      {
        data: "ujian4",
        "className": "text-center"
      },
      {
        data: "tugas5",
        "className": "text-center"
      },
      {
        data: "ujian5",
        "className": "text-center"
      },
      {
        data: "tugas6",
        "className": "text-center"
      },
      {
        data: "ujian6",
        "className": "text-center"
      },
      {
        data: "tugas7",
        "className": "text-center"
      },
      {
        data: "ujian7",
        "className": "text-center"
      },
      {
        data: "tugas8",
        "className": "text-center"
      },
      {
        data: "ujian8",
        "className": "text-center"
      },
      {
        data: "tugas9",
        "className": "text-center"
      },
      {
        data: "ujian9",
        "className": "text-center"
      },
      {
        data: "uts",
        "className": "text-center"
      },
      {
        data: "uas",
        "className": "text-center"
      }
    ],

    order: [
      [1, "asc"]
    ]
  });


});