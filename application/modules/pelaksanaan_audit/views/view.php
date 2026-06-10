<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header justify-content-between d-flex align-items-center">
					<h2 class="m-0"><i class="<?= $icon; ?> text-primary mr-2"></i>View Pelaksanaan Audit</h2>
					<a href="<?= site_url('pelaksanaan_audit'); ?>" class="btn btn-danger"><i class="fa fa-reply"></i> Kembali</a>
				</div>

				<div class="card-body">
					<!-- ================ HEADER INFO ================ -->
					<div class="mb-4">
						<h5 class="font-weight-bold border-bottom pb-2"><i class="fa fa-calendar-alt text-primary mr-2"></i><span class="text-primary">Header</span></h5>
						<table class="table table-bordered table-sm">
							<tr>
								<th width="200">Prosedur</th>
								<td><?= !empty($schedule->process_name) ? strip_tags($schedule->process_name) : htmlspecialchars($schedule->process_name_free); ?></td>
							</tr>
							<tr>
								<th>Date</th>
								<td><?= date('d/m/Y', strtotime($schedule->audit_date)); ?></td>
							</tr>
							<tr>
								<th>Department</th>
								<td><?= isset($schedule->department_name) ? $schedule->department_name : '-'; ?></td>
							</tr>
							<tr>
								<th>Auditor</th>
								<td><?= isset($schedule->auditor_name) ? $schedule->auditor_name : '-'; ?></td>
							</tr>
						</table>
					</div>

					<!-- ================ ISU PROSES ================ -->
					<div class="mb-4">
						<h5 class="font-weight-bold border-bottom pb-2"><i class="fa fa-lightbulb text-warning mr-2"></i><span class="text-warning">Isu Proses</span></h5>
						<?php if (!empty($issues)) : ?>
							<table class="table table-bordered table-sm table-hover">
								<thead class="table-light">
									<tr class="text-center">
										<th width="200">Issue</th>
										<th>Investigasi</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($issues as $issue) : ?>
										<tr>
											<td><?= htmlspecialchars($issue->description); ?></td>
											<td><?= htmlspecialchars(isset($issue->investigation) ? $issue->investigation : ''); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php else : ?>
							<p class="text-muted"><em>Tidak ada isu proses.</em></p>
						<?php endif; ?>
					</div>

					<!-- ================ LIST CHECKLIST NON STANDARD ================ -->
					<div class="mb-4">
						<h5 class="font-weight-bold border-bottom pb-2"><i class="fa fa-clipboard-list text-danger mr-2"></i><span class="text-danger">List Checklist Non Standard</span></h5>
						<?php if (!empty($ns_checklist)) : ?>
							<?php
							$ns_detail_map = [];
							if (!empty($audit_ns_details)) {
								foreach ($audit_ns_details as $d) {
									$ns_detail_map[$d->checklist_id] = $d;
								}
							}
							?>
							<table class="table table-bordered table-sm table-hover">
								<thead class="table-light text-center">
									<tr>
										<th width="30">No</th>
										<th>Checklist</th>
										<th>Catatan</th>
										<th width="100">Kategori</th>
										<th width="130">ISO</th>
										<th width="150">Pasal</th>
										<th width="80">Evidence</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($ns_checklist as $k => $item) : $k++;
										$d = isset($ns_detail_map[$item->id]) ? $ns_detail_map[$item->id] : null;
									?>
										<tr>
											<td class="text-center"><?= $k; ?></td>
											<td><?= htmlspecialchars($item->checklist_text); ?></td>
											<td><?= $d ? nl2br(htmlspecialchars($d->catatan)) : '-'; ?></td>
											<td class="text-center">
												<?php if ($d && $d->kategori) : ?>
													<span class="font-weight-bold text-<?= ($d->kategori == 'OK') ? 'success' : (($d->kategori == 'Minor') ? 'warning' : (($d->kategori == 'Major') ? 'danger' : 'info')); ?>"><?= $d->kategori; ?></span>
												<?php else : ?>-<?php endif; ?>
											</td>
											<td>
												<?php if ($d && $d->iso_id) :
													$iso = $this->db->get_where('requirements', ['id' => $d->iso_id])->row();
													echo $iso ? htmlspecialchars($iso->name) : '-';
												else : echo '-'; endif; ?>
											</td>
											<td>
												<?php if ($d && $d->pasal_id) :
													$pasal = $this->db->get_where('requirement_details', ['id' => $d->pasal_id])->row();
													echo $pasal ? htmlspecialchars($pasal->chapter) : '-';
												else : echo '-'; endif; ?>
											</td>
											<td class="text-center">
												<?php if ($d && !empty($d->file_name)) : ?>
													<a href="<?= base_url('directory/AUDIT_PELAKSANAAN/' . $this->session->company->id_perusahaan . '/' . $schedule->schedule_id . '/' . $d->file_name); ?>" target="_blank" class="btn btn-xs btn-success"><i class="fa fa-download"></i></a>
												<?php else : ?>-<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php else : ?>
							<p class="text-muted"><em>Tidak ada checklist non standard.</em></p>
						<?php endif; ?>
					</div>

					<!-- ================ LIST CHECKLIST STANDARD ================ -->
					<div class="mb-4">
						<h5 class="font-weight-bold border-bottom pb-2"><i class="fa fa-check-double text-info mr-2"></i><span class="text-info">List Checklist Standard</span></h5>
						<?php if (!empty($std_checklist)) : ?>
							<?php
							$std_detail_map = [];
							if (!empty($audit_std_details)) {
								foreach ($audit_std_details as $d) {
									$std_detail_map[$d->checklist_detail_id] = $d;
								}
							}
							?>
							<table class="table table-bordered table-sm table-hover">
								<thead class="table-light text-center">
									<tr>
										<th width="30">No</th>
										<th>Checklist</th>
										<th>Catatan</th>
										<th width="100">Kategori</th>
										<th width="130">ISO</th>
										<th width="150">Pasal</th>
										<th width="80">Evidence</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($std_checklist as $k => $item) : $k++;
										$d = isset($std_detail_map[$item->id]) ? $std_detail_map[$item->id] : null;
									?>
										<tr>
											<td class="text-center"><?= $k; ?></td>
											<td><?= isset($item->description) ? htmlspecialchars($item->description) : ''; ?></td>
											<td><?= $d ? nl2br(htmlspecialchars($d->catatan)) : '-'; ?></td>
											<td class="text-center">
												<?php if ($d && $d->kategori) : ?>
													<span class="font-weight-bold text-<?= ($d->kategori == 'OK') ? 'success' : (($d->kategori == 'Minor') ? 'warning' : (($d->kategori == 'Major') ? 'danger' : 'info')); ?>"><?= $d->kategori; ?></span>
												<?php else : ?>-<?php endif; ?>
											</td>
											<td>
												<?php if ($d && $d->iso_id) :
													$iso = $this->db->get_where('requirements', ['id' => $d->iso_id])->row();
													echo $iso ? htmlspecialchars($iso->name) : '-';
												else : echo '-'; endif; ?>
											</td>
											<td>
												<?php if ($d && $d->pasal_id) :
													$pasal = $this->db->get_where('requirement_details', ['id' => $d->pasal_id])->row();
													echo $pasal ? htmlspecialchars($pasal->chapter) : '-';
												else : echo '-'; endif; ?>
											</td>
											<td class="text-center">
												<?php if ($d && !empty($d->file_name)) : ?>
													<a href="<?= base_url('directory/AUDIT_PELAKSANAAN/' . $this->session->company->id_perusahaan . '/' . $schedule->schedule_id . '/' . $d->file_name); ?>" target="_blank" class="btn btn-xs btn-success"><i class="fa fa-download"></i></a>
												<?php else : ?>-<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php else : ?>
							<p class="text-muted"><em>Tidak ada checklist standard.</em></p>
						<?php endif; ?>
					</div>

					<!-- ================ KESIMPULAN AUDIT ================ -->
					<div class="mb-4">
						<h5 class="font-weight-bold border-bottom pb-2"><i class="fa fa-star text-success mr-2"></i><span class="text-success">Kesimpulan Audit</span></h5>

						<!-- Conformity -->
						<h6 class="font-weight-bold mt-3 mb-2">Conformity / Strong Point</h6>
						<?php if (!empty($audit_conformity)) : ?>
							<table class="table table-bordered table-sm">
								<thead class="text-center table-light">
									<tr>
										<th width="30">No</th>
										<th>Strong Point</th>
										<th width="100">Kategori</th>
										<th width="130">ISO</th>
										<th width="150">Pasal</th>
										<th width="80">Evidence</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($audit_conformity as $k => $cf) : $k++; ?>
										<tr>
											<td class="text-center"><?= $k; ?></td>
											<td><?= nl2br(htmlspecialchars($cf->description)); ?></td>
											<td class="text-center">
												<?php if ($cf->kategori) : ?>
													<span class="font-weight-bold text-<?= ($cf->kategori == 'OK') ? 'success' : (($cf->kategori == 'Minor') ? 'warning' : (($cf->kategori == 'Major') ? 'danger' : 'info')); ?>"><?= $cf->kategori; ?></span>
												<?php endif; ?>
											</td>
											<td>
												<?php if ($cf->iso_id) :
													$iso = $this->db->get_where('requirements', ['id' => $cf->iso_id])->row();
													echo $iso ? htmlspecialchars($iso->name) : '-';
												endif; ?>
											</td>
											<td>
												<?php if ($cf->pasal_id) :
													$pasal = $this->db->get_where('requirement_details', ['id' => $cf->pasal_id])->row();
													echo $pasal ? htmlspecialchars($pasal->chapter) : '-';
												endif; ?>
											</td>
											<td class="text-center">
												<?php if (!empty($cf->file_name)) : ?>
													<a href="<?= base_url('directory/AUDIT_PELAKSANAAN/' . $this->session->company->id_perusahaan . '/' . $schedule->schedule_id . '/' . $cf->file_name); ?>" target="_blank" class="btn btn-xs btn-success"><i class="fa fa-download"></i></a>
												<?php else : ?>-<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php else : ?>
							<p class="text-muted"><em>Tidak ada data conformity.</em></p>
						<?php endif; ?>

						<!-- Temuan -->
						<h6 class="font-weight-bold mt-3 mb-2">Temuan</h6>
						<?php if (!empty($audit_temuan)) : ?>
							<table class="table table-bordered table-sm">
								<thead class="text-center table-light">
									<tr>
										<th width="30">No</th>
										<th>Temuan</th>
										<th width="100">Kategori</th>
										<th width="130">ISO</th>
										<th width="150">Pasal</th>
										<th width="80">Evidence</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($audit_temuan as $k => $tm) : $k++; ?>
										<tr>
											<td class="text-center"><?= $k; ?></td>
											<td><?= nl2br(htmlspecialchars($tm->description)); ?></td>
											<td class="text-center">
												<?php if ($tm->kategori) : ?>
													<span class="font-weight-bold text-<?= ($tm->kategori == 'OK') ? 'success' : (($tm->kategori == 'Minor') ? 'warning' : (($tm->kategori == 'Major') ? 'danger' : 'info')); ?>"><?= $tm->kategori; ?></span>
												<?php endif; ?>
											</td>
											<td>
												<?php if ($tm->iso_id) :
													$iso = $this->db->get_where('requirements', ['id' => $tm->iso_id])->row();
													echo $iso ? htmlspecialchars($iso->name) : '-';
												endif; ?>
											</td>
											<td>
												<?php if ($tm->pasal_id) :
													$pasal = $this->db->get_where('requirement_details', ['id' => $tm->pasal_id])->row();
													echo $pasal ? htmlspecialchars($pasal->chapter) : '-';
												endif; ?>
											</td>
											<td class="text-center">
												<?php if (!empty($tm->file_name)) : ?>
													<a href="<?= base_url('directory/AUDIT_PELAKSANAAN/' . $this->session->company->id_perusahaan . '/' . $schedule->schedule_id . '/' . $tm->file_name); ?>" target="_blank" class="btn btn-xs btn-success"><i class="fa fa-download"></i></a>
												<?php else : ?>-<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php else : ?>
							<p class="text-muted"><em>Tidak ada data temuan.</em></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
