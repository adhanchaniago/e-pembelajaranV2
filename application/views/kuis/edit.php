<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <a href="<?= base_url() ?>kuis/master" class="btn btn-sm btn-flat btn-warning">
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
                <?= form_open('kuis/save', array('id' => 'formkuis'), array('method' => 'edit', 'guru_id' => $guru->id_guru, 'mapel_id' => $mapel->mapel_id, 'id_kuis' => $kuis->id_kuis)) ?>
                <div class="form-group">
                    <label for="nama_kuis">Nama Kuis</label>
                    <input value="<?= $kuis->nama_kuis ?>" autofocus="autofocus" onfocus="this.select()" placeholder="Nama Kuis" type="text" class="form-control" name="nama_kuis">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="topik">Topik</label>
                    <select name="topik" id="topik" class="form-control select2" style="width: 100%!important" onchange="getSoal()">
                        <?php foreach ($topik as $row) : ?>
                            <option <?= $kuis->topik_id === $row->id_topik ? "selected" : "" ?> value="<?= $row->id_topik ?>"><?= "KELAS {$row->kelas} - {$row->nama_topik}" ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block"></small>
                </div>

                <div class="form-group">
                    <label for="soal" class="control-label">Jenis Soal</label>
                    <select id="jenis_soal" name="jenis_soal" class="form-control" style="width: 100%!important">
                        <option value="pilgan" <?= $kuis->jenis_soal === 'pilgan' ? "selected" : "" ?>>Pilihan Ganda</option>
                        <option value="essay" <?= $kuis->jenis_soal === 'essay' ? "selected" : "" ?>>Essay</option>
                    </select>
                    <small class="help-block" style="color: #dc3545"><?= form_error('jenis_soal') ?></small>
                </div>

                <div class="form-group">
                    <label for="tgl_mulai">Tanggal Mulai</label>
                    <input id="tgl_mulai" name="tgl_mulai" type="text" class="datetimepicker form-control" placeholder="Tanggal Mulai">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="tgl_selesai">Tanggal Selesai</label>
                    <input id="tgl_selesai" name="tgl_selesai" type="text" class="datetimepicker form-control" placeholder="Tanggal Selesai">
                    <small class="help-block"></small>
                </div>
                <div class="form-group">
                    <label for="waktu">Waktu</label>
                    <input value="<?= $kuis->waktu ?>" placeholder="menit" type="number" class="form-control" name="waktu">
                    <small class="help-block"></small>
                </div>

                <div id="pilgan">
                    <div class="form-group">
                        <label for="jumlah_soal">Jumlah Soal</label>
                        <input value="<?= $kuis->jumlah_soal ?>" placeholder="Jumlah Soal" type="number" class="form-control" name="jumlah_soal">
                        <small class="help-block"></small>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Acak Soal</label>
                        <select name="jenis" class="form-control">
                            <option value="" disabled selected>--- Pilih ---</option>
                            <option <?= $kuis->jenis === "acak" ? "selected" : ""; ?> value="acak">Acak Soal</option>
                            <option <?= $kuis->jenis === "urut" ? "selected" : ""; ?> value="urut">Urut Soal</option>
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

<script type="text/javascript">
    var tgl_mulai = '<?= $kuis->tgl_mulai ?>';
    var terlambat = '<?= $kuis->terlambat ?>';
</script>
<script src="<?= base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?= base_url() ?>assets/dist/js/app/kuis/edit.js"></script>
<script>
    $(document).ready(function() {
        getSoal();
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

        $.get(base_url + 'kuis/getSoalByTopic', {
                topik: topik
            })
            .done(function(result) {
                document.getElementById('soal').innerHTML = '';
                var soal_id = <?= $kuis->id_soal_essay ?>;
                result.forEach(function(val) {
                    var checked = val.id_soal == soal_id ? 'checked' : '';
                    document.getElementById('soal').innerHTML += `<input type="radio" name="soal" class="flat-red" ${checked} value="${val.id_soal}"> ${removeTags(val.soal)}<br><br>`;
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