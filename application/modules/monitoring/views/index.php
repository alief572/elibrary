<style>
	p {
		margin-bottom: 0px;
	}
</style>
<div class="content d-flex flex-column flex-column-fluid p-0">
	<div class="container mt-5">
		<h1 class="text-white pt-0 font-weight-bolder bg-white-o-50 d-inline text-center rounded-lg px-5 py-1">MONITORING DOCUMENTS</h1>
		<div class="pt-3 mt-4">
			<h3 class="text-white pt-0 font-weight-bolder bg-white-o-0 rounded-lg px-0 py-1">Procedures</h3>
			<div class="d-flex justify-content-start align-items-center">

				<div class="w-250px mr-5 mb-lg-5">
					<div class="card border-0 shadow-lg rounded-lg" style="background-color: rgba(255, 255, 255,100);">
						<div class="card-body p-2 text-center">
							<!-- <img src="<?= base_url('assets/images/dashboard/prosedur.png'); ?>" alt="List Procedure" class="img-fluid" style="height: 150px;"> -->
							<h5 class="font-weight-bolder text-success" style="font-size: 48px;"><?= $dtProcedureRev; ?></h5>
							<p>Documents</p>
						</div>
						<h6 class="card-title text-center px-4">
							<a href="<?= base_url($this->uri->segment(1) . "/review"); ?>" class="text-hover-primary" title="REVIEW DOCUMENTS">
								<span class="card-label text-dark text-center font-weight-bolder">REVIEW DOCUMENTS</span>
							</a>
						</h6>
					</div>
				</div>

				<div class="w-250px mr-5 mb-lg-5">
					<div class="card border-0 shadow-lg rounded-lg " style="background-color: rgba(255, 255, 255,100);">
						<div class="card-body p-2 text-center">
							<h5 class="font-weight-bolder text-warning" style="font-size: 48px;"><?= $dtProcedureApv; ?></h5>
							<p>Documents</p>
						</div>
						<h6 class="card-title text-center px-4">
							<a href="<?= base_url($this->uri->segment(1) . "/correction"); ?>" class="text-hover-primary" title="CORRECTION DOCUMENTS">
								<span class="card-label text-dark text-center font-weight-bolder">CORRECTION DOCUMENTS</span>
							</a>
						</h6>
					</div>
				</div>

				<div class="w-250px mr-5 mb-lg-5">
					<div class="card border-0 shadow-lg rounded-lg " style="background-color: rgba(255, 255, 255,100);">
						<div class="card-body p-2 text-center">
							<h5 class="font-weight-bolder text-info" style="font-size: 48px;"><?= $dtProcedureApv; ?></h5>
							<p>Documents</p>
						</div>
						<h6 class="card-title text-center px-4">
							<a href="<?= base_url($this->uri->segment(1) . "/approval"); ?>" class="text-hover-primary" title="APPROVAL DOCUMENTS">
								<span class="card-label text-dark text-center font-weight-bolder">APPROVAL DOCUMENTS</span>
							</a>
						</h6>
					</div>
				</div>

				<div class="w-250px mr-5 mb-lg-5">
					<div class="card border-0 shadow-lg rounded-lg " style="background-color: rgba(255, 255, 255,100);">
						<div class="card-body p-2 text-center">
							<h5 class="font-weight-bolder text-primary" style="font-size: 48px;"><?= $dtProcedurePub; ?></h5>
							<p>Documents</p>
						</div>
						<h6 class="card-title text-center px-4">
							<a href="<?= base_url($this->uri->segment(1) . "/publised"); ?>" class="text-hover-primary" title="PUBLISH DOCUMENT">
								<span class="card-label text-dark text-center font-weight-bolder">PUBLISH DOCUMENTS</span>
							</a>
						</h6>
					</div>
				</div>

				<div class="w-250px mr-5 mb-lg-5">
					<div class="card border-0 shadow-lg rounded-lg " style="background-color: rgba(255, 255, 255,100);">
						<div class="card-body p-2 text-center">
							<h5 class="font-weight-bolder text-danger" style="font-size: 48px;"><?= $dtProcedureRvi; ?></h5>
							<p>Documents</p>
						</div>
						<h6 class="card-title text-center px-4">
							<a href="<?= base_url($this->uri->segment(1) . "/revision"); ?>" class="text-hover-primary" title="REVISION DOCUMENT">
								<span class="card-label text-dark text-center font-weight-bolder">REVISION DOCUMENTS</span>
							</a>
						</h6>
					</div>

				</div>

			</div>
		</div>

		<div class="pt-3">
			<h3 class="text-white mt-0 pt-0 font-weight-bolder bg-white-o-0 rounded-lg px-0 py-1">Other Document</h3>
			<div class="d-flex justify-content-start align-items-center">

				<div class="w-250px mr-5 mb-lg-5">
					<div class="card border-0 shadow-lg rounded-lg" style="background-color: rgba(255, 255, 255,100);">
						<div class="card-body p-2 text-center">
							<!-- <img src="<?= base_url('assets/images/dashboard/prosedur.png'); ?>" alt="List Procedure" class="img-fluid" style="height: 150px;"> -->
							<h5 class="font-weight-bolder text-success" style="font-size: 48px;"><?= $dtGuidesApv; ?></h5>
							<p>Documents</p>
						</div>
						<h6 class="card-title text-center px-4">
							<a href="<?= base_url($this->uri->segment(1) . "/review"); ?>" class="text-hover-primary" title="REVIEW DOCUMENTS">
								<span class="card-label text-dark text-center font-weight-bolder">REVIEW DOCUMENTS</span>
							</a>
						</h6>
					</div>
				</div>

				<div class="w-250px mr-5 mb-lg-5">
					<div class="card border-0 shadow-lg rounded-lg " style="background-color: rgba(255, 255, 255,100);">
						<div class="card-body p-2 text-center">
							<h5 class="font-weight-bolder text-warning" style="font-size: 48px;"><?= $dtGuidesRev; ?></h5>
							<p>Documents</p>
						</div>
						<h6 class="card-title text-center px-4">
							<a href="<?= base_url($this->uri->segment(1) . "/correction"); ?>" class="text-hover-primary" title="CORRECTION DOCUMENTS">
								<span class="card-label text-dark text-center font-weight-bolder">CORRECTION DOCUMENTS</span>
							</a>
						</h6>
					</div>
				</div>

				<div class="w-250px mr-5 mb-lg-5">
					<div class="card border-0 shadow-lg rounded-lg " style="background-color: rgba(255, 255, 255,100);">
						<div class="card-body p-2 text-center">
							<h5 class="font-weight-bolder text-info" style="font-size: 48px;"><?= $dtGuidesCor; ?></h5>
							<p>Documents</p>
						</div>
						<h6 class="card-title text-center px-4">
							<a href="<?= base_url($this->uri->segment(1) . "/approval"); ?>" class="text-hover-primary" title="APPROVAL DOCUMENTS">
								<span class="card-label text-dark text-center font-weight-bolder">APPROVAL DOCUMENTS</span>
							</a>
						</h6>
					</div>
				</div>

				<div class="w-250px mr-5 mb-lg-5">
					<div class="card border-0 shadow-lg rounded-lg " style="background-color: rgba(255, 255, 255,100);">
						<div class="card-body p-2 text-center">
							<h5 class="font-weight-bolder text-primary" style="font-size: 48px;"><?= $dtGuidesPub; ?></h5>
							<p>Documents</p>
						</div>
						<h6 class="card-title text-center px-4">
							<a href="<?= base_url($this->uri->segment(1) . "/publised"); ?>" class="text-hover-primary" title="PUBLISH DOCUMENT">
								<span class="card-label text-dark text-center font-weight-bolder">PUBLISH DOCUMENTS</span>
							</a>
						</h6>
					</div>
				</div>

				<div class="w-250px mr-5 mb-lg-5">
					<div class="card border-0 shadow-lg rounded-lg " style="background-color: rgba(255, 255, 255,100);">
						<div class="card-body p-2 text-center">
							<h5 class="font-weight-bolder text-danger" style="font-size: 48px;"><?= $dtGuidesRvi; ?></h5>
							<p>Documents</p>
						</div>
						<h6 class="card-title text-center px-4">
							<a href="<?= base_url($this->uri->segment(1) . "/revision"); ?>" class="text-hover-primary" title="REVISION DOCUMENT">
								<span class="card-label text-dark text-center font-weight-bolder">REVISION DOCUMENTS</span>
							</a>
						</h6>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>