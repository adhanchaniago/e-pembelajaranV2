<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <a href="<?= base_url('hasiltugas/detail/') . $essay->tugas_id ?>" class="btn btn-flat btn-sm btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>

            </div>
            <div class="col-sm-6">
                <table class="table w-100">
                    <tr>
                        <th>Nama Siswa</th>
                        <td><?= $essay->nama ?></td>
                    </tr>
                    <tr>
                        <th>NIS</th>
                        <td><?= $essay->nis ?></td>
                    </tr>
                    <tr>

                        <td><button data-target="#modal-default" data-toggle="modal" data-id="my_id_value" class="btn btn-flat bg-maroon"><i class="fa fa-search"></i> Cek Plagiasi</button></td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-4">
                <table class="table w-100">
                    <tr>
                        <th>Kelas</th>
                        <td><?= $essay->nama_kelas ?></td>
                    </tr>
                    <?= form_open('hasiltugas/savenilai', array('id' => 'simpan_nilai'), array('id' => $essay->id)) ?>
                    <tr>
                        <th>Nilai</th>
                        <td>
                            <div class="form-group">
                                <input type="number" name="nilai" min="0" max="100" value="<?= $essay->nilai ?>" class="form-control">
                                <small class="help-block"></small>
                            </div>
                        </td>
                        <td> <button id="submit" type="submit" class="btn btn-flat bg-purple"><i class="fa fa-save"></i> Simpan</button></td>
                    </tr>
                    <?= form_close() ?>
                </table>
            </div>
        </div>
    </div>
    <div class="table-responsive px-4 pb-3" style="border: 0">
        Soal: <?= $essay->list_soal ?>
    </div>
    <div class="table-responsive px-4 pb-3" style="border: 0">
        Jawaban: <?= $essay->list_jawaban ?>
    </div>
</div>
<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cek Plagiasi</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive px-4 pb-3" style="border: 0">
                    <table id="tugas" class="w-100 table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Siswa Pembanding</th>
                                <th>Presentase Plagiasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($plagiasi as $plagiasi) { ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td><?= $plagiasi['nama']; ?></td>
                                    <td><?php printf("%.2f", $plagiasi['hasil'] * 100); ?> %</td>
                                </tr>
                            <?php $no++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script type="text/javascript">
    var back = '<?= base_url('hasiltugas/detail/') . $essay->tugas_id ?>';
</script>
<script src="<?= base_url() ?>assets/dist/js/app/tugas/hasil_essay.js"></script>