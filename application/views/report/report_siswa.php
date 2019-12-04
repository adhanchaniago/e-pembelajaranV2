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
            <div class="col-sm-4">
            </div>
            <div class="form-group col-sm-4 text-center">
                <select id="kelas_filter" class="form-control select2" style="width:100% !important">
                    <option value="10">Kelas 10</option>
                    <option value="11">Kelas 11</option>
                    <option value="12">Kelas 12</option>
                </select>
            </div>
            <div class="col-sm-4">
                <div class="pull-right">
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive px-4 pb-3">
        <table id="report" style="border:3px" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th width="25">No.</th>
                    <th width="150">Mapel</th>
                    <th width="60">Tugas 1</th>
                    <th width="60">Ujian 1</th>
                    <th width="60">Tugas 2</th>
                    <th width="60">Ujian 2</th>
                    <th width="60">Tugas 3</th>
                    <th width="60">Ujian 3</th>
                    <th width="60">Tugas 4</th>
                    <th width="60">Ujian 4</th>
                    <th width="60">Tugas 5</th>
                    <th width="60">Ujian 5</th>
                    <th width="60">Tugas 6</th>
                    <th width="60">Ujian 6</th>
                    <th width="60">Tugas 7</th>
                    <th width="60">Ujian 7</th>
                    <th width="60">Tugas 8</th>
                    <th width="60">Ujian 8</th>
                    <th width="60">Tugas 9</th>
                    <th width="60">Ujian 9</th>
                    <th width="60">Tugas 10</th>
                    <th width="60">Ujian 10</th>
                    <th width="60">UTS</th>
                    <th width="60">UAS</th>
                </tr>
            </thead>

        </table>
    </div>
</div>

<?php if ($this->ion_auth->in_group('siswa')) : ?>
    <script type="text/javascript">
        let url = ''
        $(document).ready(function() {
            let sel = document.getElementById("kelas_filter");
            let id_siswa = '<?= $siswa->id_siswa ?>';
            let kelas = sel.value;
            let src = '<?= base_url() ?>report/data_siswa';
            url = src + '/' + id_siswa + '/' + kelas;
        });
    </script>
<?php endif; ?>
<script src="<?= base_url() ?>assets/dist/js/app/report/data2.js"></script>


<?php if ($this->ion_auth->in_group('siswa')) : ?>
    <script type="text/javascript">
        $('#kelas_filter').on('change', function() {
            let sel = document.getElementById("kelas_filter");
            let id_siswa = '<?= $siswa->id_siswa ?>';
            let kelas = sel.value;
            let src = '<?= base_url() ?>report/data_siswa';
            let url = src + '/' + id_siswa + '/' + kelas;
            table.ajax.url(url).load();
        });
    </script>
<?php endif; ?>