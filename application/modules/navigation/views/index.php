<style>
	.nav-card-item {
		border-radius: 30px 5px 30px 5px !important;
		background-color: rgba(255, 255, 255, 0.50) !important;
		border: 1px solid rgba(255, 255, 255, 0.4) !important;
		transition: transform 0.22s ease, box-shadow 0.22s ease, background-color 0.22s ease;
		text-decoration: none !important;
		display: block;
		height: 100%;
	}

	.nav-card-item:hover {
		transform: translateY(-5px);
		box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25) !important;
		background-color: rgba(255, 255, 255, 0.75) !important;
		text-decoration: none !important;
	}

	.nav-card-img {
		height: 140px;
		max-width: 100%;
		object-fit: contain;
		transition: transform 0.2s ease;
	}

	.nav-card-item:hover .nav-card-img {
		transform: scale(1.05);
	}

	.nav-card-label {
		font-size: 13px;
		font-weight: 800;
		color: #1a1a1a !important;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		line-height: 1.4;
		margin: 0;
		text-align: center;
	}
</style>

<div class="content d-flex flex-column flex-column-fluid p-0">
	<div class="d-flex flex-column-fluid justify-content-between align-items-top">
		<div class="container mt-5">
			<div class="d-flex justify-content-between align-items-center mb-5">
				<h1 class="text-white mb-0 mt-0 pt-0 font-weight-bolder bg-white-o-0 rounded-lg px-3 py-2">
					PUBLISHED DOCUMENTS
				</h1>
				<?php if (!empty($is_admin)) : ?>
					<a href="<?= base_url('navigation/cards'); ?>" class="btn btn-light font-weight-bolder px-4 py-2 shadow-sm" title="Kelola Card Navigation">
						<i class="fa fa-cog mr-1 text-dark"></i> <span class="text-dark">Kelola Card</span>
					</a>
				<?php endif; ?>
			</div>

			<div class="row justify-content-center">
				<div class="col-12 col-xl-11 mb-10">
					<div class="row">
						<?php if (!empty($cards)) :
							foreach ($cards as $card) :
								$href = (strpos($card->link, 'http://') === 0 || strpos($card->link, 'https://') === 0)
									? $card->link
									: base_url(ltrim($card->link, '/'));
								$imgSrc = base_url('assets/images/dashboard/' . $card->picture);
						?>
							<div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-5">
								<a href="<?= $href; ?>" class="nav-card-item shadow-lg p-4 d-flex flex-column justify-content-between" title="<?= htmlspecialchars($card->name); ?>">
									<div class="d-flex justify-content-center align-items-center mb-3" style="min-height: 140px;">
										<?php if (!empty($card->picture) && file_exists(FCPATH . 'assets/images/dashboard/' . $card->picture)) : ?>
											<img src="<?= $imgSrc; ?>" alt="<?= htmlspecialchars($card->name); ?>" class="nav-card-img img-fluid">
										<?php else : ?>
											<img src="<?= base_url('assets/images/dashboard/default.png'); ?>" alt="<?= htmlspecialchars($card->name); ?>" class="nav-card-img img-fluid" onerror="this.src='<?= base_url('assets/img/default.png'); ?>'">
										<?php endif; ?>
									</div>
									<div class="d-flex align-items-center justify-content-center" style="min-height: 50px;">
										<span class="nav-card-label"><?= htmlspecialchars($card->name); ?></span>
									</div>
								</a>
							</div>
						<?php endforeach;
						else : ?>
							<div class="col-12 text-center text-white py-10">
								<i class="fa fa-th-large fa-3x mb-3 text-white-50"></i>
								<p class="mb-0 font-size-lg font-weight-bold">Belum ada card aktif di navigasi.</p>
								<?php if (!empty($is_admin)) : ?>
									<a href="<?= base_url('navigation/cards'); ?>" class="btn btn-light font-weight-bold mt-4">
										<i class="fa fa-plus-circle mr-1"></i> Tambah Card Navigasi
									</a>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
