<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?= $judul ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-offset-3 col-sm-6">
                <div class="my-2">
                    <div class="form-horizontal form-inline">
                        <a href="<?= base_url('topik') ?>" class="btn btn-default btn-xs">
                            <i class="fa fa-arrow-left"></i> Batal
                        </a>
                        <div class="pull-right">
                            <span> Jumlah : </span><label for=""><?= $banyak ?></label>
                        </div>
                    </div>
                </div>
                <?= form_open('topik/save', array('id' => 'topik'), array('mode' => 'add')) ?>
                <table id="form-table" class="table text-center table-condensed">
                    <thead>
                        <tr>
                            <th># No</th>
                            <th>Kelas</th>
                            <th>Topik</th>
                            <th>Mapel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 1; $i <= $banyak; $i++) : ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control input-sm select2" name="kelas[<?= $i ?>]" required="required" style="width: 100%!important">
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                        <small class="help-block text-right"></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input autofocus="autofocus" onfocus="this.select()" required="required" autocomplete="off" type="text" name="nama_topik[<?= $i ?>]" class="form-control">
                                        <span class="d-none">DON'T DELETE THIS</span>
                                        <small class="help-block text-right"></small>
                                    </div>
                                </td>
                                <td width="200">
                                    <div class="form-group">
                                        <?php if ($this->ion_auth->is_admin()) { ?>
                                            <select required="required" name="mapel_id[<?= $i ?>]" class="form-control input-sm select2" style="width: 100%!important">
                                                <option value="" disabled selected>-- Pilih --</option>
                                                <?php foreach ($mapel as $j) : ?>
                                                    <option value="<?= $j->id_mapel ?>"><?= $j->nama_mapel ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="help-block text-right"></small>
                                        <?php } else { ?>
                                            <input type="text" readonly value="<?= $mapel->nama_mapel ?>" class="form-control" style="width: 100%!important">
                                            <input type="hidden" readonly value="<?= $mapel->mapel_id ?>" name="mapel_id[<?= $i ?>]" class="form-control input-sm" style="width: 100%!important">

                                        <?php } ?>

                                    </div>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
                <button id="submit" type="submit" class="mb-4 btn btn-block btn-flat bg-purple">
                    <i class="fa fa-save"></i> Simpan
                </button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var inputs = '';
    var banyak = '<?= $banyak; ?>';
</script>

<script src="<?= base_url() ?>assets/dist/js/app/master/topik/add.js"></script>