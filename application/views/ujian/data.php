<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $subjudul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">

    </div>
    <?= form_open('ujian/delete', array('id' => 'bulk')) ?>
    <div class="table-responsive px-4 pb-3" style="border: 0">
        <table id="ujian" class="w-100 table table-striped table-bordered table-hover">
            <thead>
                <tr>

                    <th>No.</th>
                    <th>Kelas</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tfoot>
                <tr>

                    <th>No.</th>
                    <th>Kelas</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <?= form_close(); ?>
</div>

<script type="text/javascript">
    var id_guru = '<?= $guru->id_guru ?>';
</script>

<script src="<?= base_url() ?>assets/dist/js/app/ujian/data.js"></script>