<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <?= form_open('', array('id' => 'ujian')) ?>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <a href="<?= base_url() ?>hasilujian" class="btn btn-flat btn-sm btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                <div class="pull-right">
                    <button type="submit" class="mb-4 btn btn-block btn-flat bg-purple">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </div>
            <?= form_hidden('id_guru', $guru->id_guru); ?>
            <?= form_hidden('id_kelas', $id_kelas); ?>
            <?= form_hidden('id_mapel', $guru->mapel_id); ?>
        </div>
    </div>
    <div class="table-responsive px-4 pb-3" style="border: 0">

        <table id="detail_hasil" class="w-100 table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th width="30%">Nama</th>
                    <th width="20%">Kelas</th>
                    <th>Nilai UTS</th>
                    <th>Nilai UAS</th>
                    <th></th>
                    <th></th>

                </tr>
            </thead>
        </table>
    </div>
    <?= form_close() ?>
</div>

<script type="text/javascript">
    let id = '<?= $this->uri->segment(3) ?>';
</script>
<script src="<?= base_url() ?>assets/dist/js/app/ujian/detail_hasil.js"></script>