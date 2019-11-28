<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <a href="<?= base_url() ?>tugas/master" class="btn btn-sm btn-flat btn-warning">
                <i class="fa fa-arrow-left"></i> Batal
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="alert bg-purple">
                    <h4>Mata Pelajaran <i class="fa fa-book pull-right"></i></h4>
                    <p><?= $mapel->nama_mapel ?></p>
                </div>
                <div class="alert bg-purple">
                    <h4>Guru <i class="fa fa-address-book-o pull-right"></i></h4>
                    <p><?= $guru->nama_guru ?></p>
                </div>
            </div>
            <div class="col-sm-4">
                <?= form_open('tugas/save', array('id' => 'formtugas'), array('method' => 'add', 'guru_id' => $guru->id_guru, 'mapel_id' => $mapel->mapel_id)) ?>
                <div class="form-group">
                    <label for="nama_tugas">Nama Tugas</label>
                    <input autofocus="autofocus" onfocus="this.select()" placeholder="Nama Tugas" type="text" class="form-control" name="nama_tugas">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="topik">Topik</label>
                    <select name="topik" id="topik" class="form-control select2" style="width: 100%!important" onchange="getSoal()">
                        <option value="" disabled selected>Pilih Topik</option>
                        <?php foreach ($topik as $row) : ?>
                            <option value="<?= $row->id_topik ?>"><?= $row->nama_topik ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block"></small>
                </div>

                <div class="form-group">
                    <label for="soal" class="control-label">Jenis Soal</label>
                    <select id="jenis_soal" name="jenis_soal" class="form-control" style="width: 100%!important">
                        <option value="pilgan">Pilihan Ganda</option>
                        <option value="essay">Essay</option>
                    </select>
                    <small class="help-block" style="color: #dc3545"><?= form_error('jenis_soal') ?></small>
                </div>

                <div class="form-group">
                    <label for="tgl_mulai">Tanggal Mulai</label>
                    <input name="tgl_mulai" type="text" class="datetimepicker form-control" placeholder="Tanggal Mulai">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="tgl_selesai">Tanggal Selesai</label>
                    <input name="tgl_selesai" type="text" class="datetimepicker form-control" placeholder="Tanggal Selesai">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="waktu">Waktu</label>
                    <input placeholder="menit" type="number" class="form-control" min="1" name="waktu">
                    <small class="help-block"></small>
                </div>

                <div id="pilgan">
                    <div class="form-group">
                        <label for="jumlah_soal">Jumlah Soal</label>
                        <input placeholder="Jumlah Soal" type="number" class="form-control" name="jumlah_soal">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Acak Soal</label>
                        <select name="jenis" class="form-control">
                            <option value="" disabled selected>--- Pilih ---</option>
                            <option value="acak">Acak Soal</option>
                            <option value="urut">Urut Soal</option>
                        </select>
                        <small class="help-block"></small>
                    </div>
                </div>
                <div id="essay" class="form-group">
                    <label for="soal">Pilih Soal</label> (Jika tidak ada soal silahkan buat soal terlebih dahulu <a href="<?= base_url('soal') ?>">disini</a>)
                    <div>
                        <div class="form-group" id="soal" style="text-align:justify;">
                        </div>
                    </div>
                    <small class="help-block"></small>
                </div>

                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-default btn-flat">
                        <i class="fa fa-rotate-left"></i> Reset
                    </button>
                    <button id="submit" type="submit" class="btn btn-flat bg-purple"><i class="fa fa-save"></i> Simpan</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?= base_url() ?>assets/dist/js/app/tugas/add.js"></script>

<script>
    $(document).ready(function() {
        if ($("#jenis_soal").val() == 'pilgan') {
            $("#pilgan").show()
            $("#essay").hide()
        } else {
            $("#pilgan").hide()
            $("#essay").show()
        }

        $("#jenis_soal").change(function() {
            if ($("#jenis_soal").val() == 'pilgan') {
                $("#pilgan").show()
                $("#essay").hide()
            } else {
                $("#pilgan").hide()
                $("#essay").show()
            }
        })
    });

    function getSoal() {
        var topik = $('#topik').val();
        // console.log(topik)

        $.get(base_url + 'tugas/getSoalByTopic', {
                topik: topik
            })
            .done(function(result) {
                document.getElementById('soal').innerHTML = ''
                result.forEach(function(val) {
                    document.getElementById('soal').innerHTML += '<input type="radio" name="soal" class="flat-red" value="' + val.id_soal + '"> ' + removeTags(val.soal) + '<br>';

                })
                $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                    checkboxClass: 'icheckbox_flat-green',
                    radioClass: 'iradio_flat-green'
                })
            });

    }

    function removeTags(str) {
        if ((str === null) || (str === ''))
            return false;
        else
            str = str.toString();
        return str.replace(/(<([^>]+)>)/ig, '');
    }
</script>