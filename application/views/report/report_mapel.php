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
                    <th width="150">Nama</th>
                    <th width="60">Tugas 1</th>
                    <th width="60">Kuis 1</th>
                    <th width="60">Tugas 2</th>
                    <th width="60">Kuis 2</th>
                    <th width="60">Tugas 3</th>
                    <th width="60">Kuis 3</th>
                    <th width="60">Tugas 4</th>
                    <th width="60">Kuis 4</th>
                    <th width="60">Tugas 5</th>
                    <th width="60">Kuis 5</th>
                    <th width="60">Tugas 6</th>
                    <th width="60">Kuis 6</th>
                    <th width="60">Tugas 7</th>
                    <th width="60">Kuis 7</th>
                    <th width="60">Tugas 8</th>
                    <th width="60">Kuis 8</th>
                    <th width="60">Tugas 9</th>
                    <th width="60">Kuis 9</th>
                    <th width="60">Tugas 10</th>
                    <th width="60">Kuis 10</th>
                    <th width="60">UTS</th>
                    <th width="60">UAS</th>
                </tr>
            </thead>

        </table>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/report/data.js"></script>


<?php if ($this->ion_auth->in_group('guru')) : ?>
    <script type="text/javascript">
        $(document).ready(function() {
            let id_mapel = 13;
            let src = '<?= base_url() ?>report/data';
            let url = src + '/' + id_mapel;

            table.ajax.url(url).load();
        });
    </script>
<?php endif; ?>