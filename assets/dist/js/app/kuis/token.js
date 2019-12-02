$(document).ready(function () {
    ajaxcsrf();

    $('#btncek').on('click', function () {
        var token = $('#token').val();
        var idKuis = $(this).data('id');
        if (token === '') {
            Swal('Gagal', 'Token harus diisi', 'error');
        } else {
            var key = $('#id_kuis').data('key');
            $.ajax({
                url: base_url + 'kuis/cektoken/',
                type: 'POST',
                data: {
                    id_kuis: idKuis,
                    token: token
                },
                cache: false,
                success: function (result) {
                    Swal({
                        "type": result.status ? "success" : "error",
                        "title": result.status ? "Berhasil" : "Gagal",
                        "text": result.status ? "Token Benar" : "Token Salah"
                    }).then((data) => {
                        if (result.status) {
                            location.href = base_url + 'kuis/?key=' + key;
                        }
                    });
                }
            });
        }
    });

    var time = $('.countdown');
    if (time.length) {
        countdown(time.data('time'));
    }
});