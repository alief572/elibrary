<div class="content d-flex flex-column flex-column-fluid">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Generate Published PDFs</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="alert alert-info text-center">
                            <h4><?= $total; ?></h4>
                            <small>Total Published</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-success text-center">
                            <h4><?= $results['success']; ?></h4>
                            <small>Generated</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-warning text-center">
                            <h4><?= $results['skipped']; ?></h4>
                            <small>Skipped (exists)</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-danger text-center">
                            <h4><?= $results['failed']; ?></h4>
                            <small>Failed</small>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results['details'] as $d) : ?>
                            <tr>
                                <td><?= $d['id']; ?></td>
                                <td><?= $d['name']; ?></td>
                                <td>
                                    <?php if ($d['status'] == 'generated') : ?>
                                        <span class="label label-success"><?= $d['status']; ?></span>
                                    <?php elseif ($d['status'] == 'skipped') : ?>
                                        <span class="label label-warning"><?= $d['status']; ?></span>
                                    <?php else : ?>
                                        <span class="label label-danger"><?= $d['status']; ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <a href="<?= base_url('procedures'); ?>" class="btn btn-primary mt-3">
                    <i class="fa fa-arrow-left"></i> Back to Procedures
                </a>
            </div>
        </div>
    </div>
</div>
