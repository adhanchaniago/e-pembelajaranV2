<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">

		<!-- Sidebar user panel (optional) -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?= base_url() ?>assets/dist/img/user1.png" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p><?= $user->username ?></p>
				<small><?= $user->email ?></small>
			</div>
		</div>

		<ul class="sidebar-menu" data-widget="tree">
			<li class="header">MAIN MENU</li>
			<!-- Optionally, you can add icons to the links -->
			<?php
			$page = $this->uri->segment(1);
			$master = ["kelas", "mapel", "topik", "guru", "siswa"];
			$master2 = ["tugas", "hasiltugas", "kuis", "hasilkuis"];
			$users = ["users"];
			?>
			<li class="<?= $page === 'dashboard' ? "active" : "" ?>"><a href="<?= base_url('dashboard') ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
			<!-- ini menu admin -->
			<?php if ($this->ion_auth->is_admin()) : ?>
				<li class="treeview <?= in_array($page, $master)  ? "active menu-open" : ""  ?>">
					<a href="#"><i class="fa fa-folder"></i> <span>Data</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">

						<li class="<?= $page === 'kelas' ? "active" : "" ?>">
							<a href="<?= base_url('kelas') ?>">
								<i class="fa fa-circle-o"></i>
								Kelas
							</a>
						</li>
						<li class="<?= $page === 'mapel' ? "active" : "" ?>">
							<a href="<?= base_url('mapel') ?>">
								<i class="fa fa-circle-o"></i>
								Mata Pelajaran
							</a>
						</li>
						<li class="<?= $page === 'topik' ? "active" : "" ?>">
							<a href="<?= base_url('topik') ?>">
								<i class="fa fa-circle-o"></i>
								Topik
							</a>
						</li>
						<li class="<?= $page === 'guru' ? "active" : "" ?>">
							<a href="<?= base_url('guru') ?>">
								<i class="fa fa-circle-o"></i>
								Guru
							</a>
						</li>
						<li class="<?= $page === 'siswa' ? "active" : "" ?>">
							<a href="<?= base_url('siswa') ?>">
								<i class="fa fa-circle-o"></i>
								Siswa
							</a>
						</li>
					</ul>
				</li>

			<?php endif; ?>
			<!-- ini menu topik guru -->
			<?php if ($this->ion_auth->in_group('guru')) : ?>
				<li class="<?= $page === 'topik' ? "active" : "" ?>">
					<a href="<?= base_url('topik') ?>" rel="noopener noreferrer">
						<i class="fa fa-circle-o"></i>
						Topik
					</a>
				</li>
			<?php endif; ?>
			<!-- ini menu bank soal guru -->
			<?php if ($this->ion_auth->in_group('guru')) : ?>
				<li class="<?= $page === 'soal' ? "active" : "" ?>">
					<a href="<?= base_url('soal') ?>" rel="noopener noreferrer">
						<i class="fa fa-file-text-o"></i> <span>Bank Soal</span>
					</a>
				</li>
			<?php endif; ?>
			<!-- ini menu tugas guru -->
			<?php if ($this->ion_auth->in_group('guru')) : ?>
				<li class="treeview <?= in_array($page, $master2)  ? "active menu-open" : ""  ?>">
					<a href="#"><i class="fa fa-folder"></i> <span>Tugas & Kuis</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="<?= $page === 'tugas' ? "active" : "" ?>">
							<a href="<?= base_url('tugas/master') ?>" rel="noopener noreferrer">
								<i class="fa fa-paperclip"></i> <span>Buat Tugas</span>
							</a>
						</li>
						<li class="<?= $page === 'hasiltugas' ? "active" : "" ?>">
							<a href="<?= base_url('hasiltugas') ?>" rel="noopener noreferrer">
								<i class="fa fa-file"></i> <span>Hasil Tugas</span>
							</a>
						</li>
						<li class="<?= $page === 'kuis' ? "active" : "" ?>">
							<a href="<?= base_url('kuis/master') ?>" rel="noopener noreferrer">
								<i class="fa fa-clipboard"></i> <span>Buat Kuis</span>
							</a>
						</li>
						<li class="<?= $page === 'hasilkuis' ? "active" : "" ?>">
							<a href="<?= base_url('hasilkuis') ?>" rel="noopener noreferrer">
								<i class="fa fa-file"></i> <span>Hasil Kuis</span>
							</a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
			<!-- ini menu tugas siswa -->
			<?php if ($this->ion_auth->in_group('siswa')) : ?>

				<li class="<?= $page === 'tugas' ? "active" : "" ?>">
					<a href="<?= base_url('tugas/list') ?>" rel="noopener noreferrer">
						<i class="fa fa-paperclip"></i> <span>Tugas</span>
					</a>
				</li>
				<li class="<?= $page === 'kuis' ? "active" : "" ?>">
					<a href="<?= base_url('kuis/list') ?>" rel="noopener noreferrer">
						<i class="fa fa-clipboard"></i> <span>Kuis</span>
					</a>
				</li>
			<?php endif; ?>
			<!-- ini menu ujian guru -->
			<?php if ($this->ion_auth->in_group('guru')) : ?>
				<li class="<?= $page === 'ujian' ? "active" : "" ?>">
					<a href="<?= base_url('ujian') ?>" rel="noopener noreferrer">
						<i class="fa fa-clipboard"></i> <span>Ujian</span>
					</a>
				</li>

			<?php endif; ?>
			<!-- ini menu ujian siswa -->
			<!-- <?php if ($this->ion_auth->in_group('siswa')) : ?>
				<li class="<?= $page === 'ujian' ? "active" : "" ?>">
					<a href="<?= base_url('ujian/list') ?>" rel="noopener noreferrer">
						<i class="fa fa-clipboard"></i> <span>Ujian</span>
					</a>
				</li>
			<?php endif; ?> -->
			<!-- ini menu laporan guru -->
			<?php if ($this->ion_auth->in_group('guru')) : ?>
				<li class="<?= $page === 'report' ? "active" : "" ?>">
					<a href="<?= base_url('report') ?>" rel="noopener noreferrer">
						<i class="fa fa-clipboard"></i> <span>Laporan</span>
					</a>
				</li>
			<?php endif; ?>
			<!-- ini menu laporan siswa -->
			<?php if ($this->ion_auth->in_group('siswa')) : ?>
				<li class="header">LAPORAN</li>
				<li class="<?= $page === 'report' ? "active" : "" ?>">
					<a href="<?= base_url('report') ?>" rel="noopener noreferrer">
						<i class="fa fa-clipboard"></i> <span>Laporan Belajar</span>
					</a>
				</li>
			<?php endif; ?>
			<!-- ini menu admin -->
			<?php if ($this->ion_auth->is_admin()) : ?>
				<li class="header">ADMINISTRATOR</li>
				<li class="<?= $page === 'users' ? "active" : "" ?>">
					<a href="<?= base_url('users') ?>" rel="noopener noreferrer">
						<i class="fa fa-users"></i> <span>User Management</span>
					</a>
				</li>
				<li class="<?= $page === 'settings' ? "active" : "" ?>">
					<a href="<?= base_url('settings') ?>" rel="noopener noreferrer">
						<i class="fa fa-cog"></i> <span>Settings</span>
					</a>
				</li>
			<?php endif; ?>
		</ul>

	</section>
	<!-- /.sidebar -->
</aside>