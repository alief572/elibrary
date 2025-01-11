<div class="">
    <ul class="nav nav-tabs nav-fill nav-success nav-pills mb-3 border-0" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link font-weight-bolder w-100 active" id="IK-tab" data-toggle="tab" data-target="#IK" type="button" role="tab" aria-controls="IK" aria-selected="true">IK</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link font-weight-bolder w-100" id="CMC-tab" data-toggle="tab" data-target="#CMC" type="button" role="tab" aria-controls="CMC" aria-selected="false">CMC</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link font-weight-bolder w-100" id="Template-tab" data-toggle="tab" data-target="#Template" type="button" role="tab" aria-controls="Template" aria-selected="false">Template</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link font-weight-bolder w-100" id="UBLK-tab" data-toggle="tab" data-target="#UBLK" type="button" role="tab" aria-controls="UBLK" aria-selected="false">UBLK</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link font-weight-bolder w-100" id="Sertifikat-tab" data-toggle="tab" data-target="#Sertifikat" type="button" role="tab" aria-controls="Sertifikat" aria-selected="false">Format Sertifikat</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link font-weight-bolder w-100" id="Analisa-tab" data-toggle="tab" data-target="#Analisa" type="button" role="tab" aria-controls="Analisa" aria-selected="false">Analisa Drift</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link font-weight-bolder w-100" id="SertCalibrator-tab" data-toggle="tab" data-target="#SertCalibrator" type="button" role="tab" aria-controls="SertCalibrator" aria-selected="false">Sertifikat Calibrator</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link font-weight-bolder w-100" id="CekAntara-tab" data-toggle="tab" data-target="#CekAntara" type="button" role="tab" aria-controls="CekAntara" aria-selected="false">Cek Antara</button>
        </li>
    </ul>

    <div class="tab-content ">
        <div class="tab-pane active" id="IK" role="tabpanel" aria-labelledby="IK-tab">
            <div id="accDataIK" role="tablist" aria-multiselectable="true">

                <!-- IK -->
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="30">No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center" width="150">Last Update</th>
                            <th class="text-center" width="200">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 0;
                        if (isset($ArrDoc[1])):
                            foreach ($ArrDoc[1] as $d1): $n++; ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $n; ?></td>
                                    <td class="align-middle"><?= $d1->name; ?></td>
                                    <td class="align-middle text-center"><?= $d1->created_at; ?></td>
                                    <td class="align-middle text-center">
                                        <a href="<?= base_url() . $file . "IK/" . $d1->file; ?>" target="_blank" class="btn btn-icon btn-xs btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <a href="<?= base_url() . $file . "IK/" . $d1->file; ?>" target="_blank" download="<?= $d1->name; ?>" class="btn py-1 btn-xs btn-success"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="4">
                                    <p class="h5"> Not available data </p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="CMC" role="tabpanel" aria-labelledby="CMC-tab">
            <div id="accDataCMC" role="tablist" aria-multiselectable="true">

                <!-- CMC -->
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="30">No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center" width="150">Last Update</th>
                            <th class="text-center" width="150">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 0;
                        if (isset($ArrDoc[2])):
                            foreach ($ArrDoc[2] as $d1): $n++; ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $n; ?></td>
                                    <td class="align-middle"><?= $d1->name; ?></td>
                                    <td class="align-middle text-center"><?= $d1->created_at; ?></td>
                                    <td class="align-middle text-center"><a href="<?= base_url() . $file . "CMC/" . $d1->file; ?>" class="btn py-1 btn-xs btn-success"><i class="fa fa-download" aria-hidden="true"></i> Download</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="4">
                                    <p class="h5"> Not available data </p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>

        <div class="tab-pane" id="Template" role="tabpanel" aria-labelledby="Template-tab">
            <div id="accDataTEMP" role="tablist" aria-multiselectable="true">
                <!-- TEMPLATE -->
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="30">No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center" width="150">Last Update</th>
                            <th class="text-center" width="150">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 0;
                        if (isset($ArrDoc[3])):
                            foreach ($ArrDoc[3] as $d1): $n++; ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $n; ?></td>
                                    <td class="align-middle"><?= $d1->name; ?></td>
                                    <td class="align-middle text-center"><?= $d1->created_at; ?></td>
                                    <td class="align-middle text-center"><a href="<?= base_url() . $file . "TEMPLATE/" . $d1->file; ?>" class="btn py-1 btn-xs btn-success"><i class="fa fa-download" aria-hidden="true"></i> Download</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="4">
                                    <p class="h5"> Not available data </p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- <iframe style="pointer-events:visibleStroke;" onclick="false" oncontextmenu="false" src="<?= base_url() . $file . "TEMPLATE/" . $d1->file; ?>#toolbar=0&navpanes=0" frameborder="1" width="100%" height="500"></iframe> -->
            </div>
        </div>

        <div class="tab-pane" id="UBLK" role="tabpanel" aria-labelledby="UBLK-tab">
            <div id="accDataUBLK" role="tablist" aria-multiselectable="true">
                <!-- UBLK -->
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="30">No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center" width="150">Last Update</th>
                            <th class="text-center" width="200">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 0;
                        if (isset($ArrDoc[4])):
                            foreach ($ArrDoc[4] as $d1): $n++; ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $n; ?></td>
                                    <td class="align-middle"><?= $d1->name; ?></td>
                                    <td class="align-middle text-center"><?= $d1->created_at; ?></td>
                                    <td class="align-middle text-center">
                                        <a href="<?= base_url() . $file . "UBLK/" . $d1->file; ?>" target="_blank" class="btn btn-icon btn-xs btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <a href="<?= base_url() . $file . "UBLK/" . $d1->file; ?>" target="_blank" download="<?= $d1->name; ?>" class="btn py-1 btn-xs btn-success"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="4">
                                    <p class="h5"> Not available data </p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="Sertifikat" role="tabpanel" aria-labelledby="Sertifikat-tab">
            <div id="accDataSERT" role="tablist" aria-multiselectable="true">
                <!-- SERTIFIKAT -->
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="30">No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center" width="150">Last Update</th>
                            <th class="text-center" width="200">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 0;
                        if (isset($ArrDoc[5])):
                            foreach ($ArrDoc[5] as $d1): $n++; ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $n; ?></td>
                                    <td class="align-middle"><?= $d1->name; ?></td>
                                    <td class="align-middle text-center"><?= $d1->created_at; ?></td>
                                    <td class="align-middle text-center">
                                        <a href="<?= base_url() . $file . "FORMAT_SERTIFIKAT/" . $d1->file; ?>" target="_blank" class="btn btn-icon btn-xs btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <a href="<?= base_url() . $file . "FORMAT_SERTIFIKAT/" . $d1->file; ?>" target="_blank" download="<?= $d1->name; ?>" class="btn py-1 btn-xs btn-success"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="4">
                                    <p class="h5"> Not available data </p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="Analisa" role="tabpanel" aria-labelledby="Analisa-tab">
            <div id="accDataANALISA" role="tablist" aria-multiselectable="true">
                <!-- ANALISA -->
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="30">No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center" width="150">Last Update</th>
                            <th class="text-center" width="200">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 0;
                        if (isset($ArrDoc[6])):
                            foreach ($ArrDoc[6] as $d1): $n++; ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $n; ?></td>
                                    <td class="align-middle"><?= $d1->name; ?></td>
                                    <td class="align-middle text-center"><?= $d1->created_at; ?></td>
                                    <td class="align-middle text-center">
                                        <a href="<?= base_url() . $file . "ANALISA_DRIFT/" . $d1->file; ?>" target="_blank" class="btn btn-icon btn-xs btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <a href="<?= base_url() . $file . "ANALISA_DRIFT/" . $d1->file; ?>" target="_blank" download="<?= $d1->name; ?>" class="btn py-1 btn-xs btn-success"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="4">
                                    <p class="h5"> Not available data </p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="SertCalibrator" role="tabpanel" aria-labelledby="SertCalibrator-tab">
            <div id="accDataCALIBRATOR" role="tablist" aria-multiselectable="true">
                <!-- CALIBRATOR -->
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="30">No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center" width="150">Last Update</th>
                            <th class="text-center" width="200">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 0;
                        if (isset($ArrDoc[7])):
                            foreach ($ArrDoc[7] as $d1): $n++; ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $n; ?></td>
                                    <td class="align-middle"><?= $d1->name; ?></td>
                                    <td class="align-middle text-center"><?= $d1->created_at; ?></td>
                                    <td class="align-middle text-center">
                                        <a href="<?= base_url() . $file . "SERTIFIKAT_KALIBRATOR/" . $d1->file; ?>" target="_blank" class="btn btn-icon btn-xs btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <a href="<?= base_url() . $file . "SERTIFIKAT_KALIBRATOR/" . $d1->file; ?>" target="_blank" download="<?= $d1->name; ?>" class="btn py-1 btn-xs btn-success"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="4">
                                    <p class="h5"> Not available data </p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane" id="CekAntara" role="tabpanel" aria-labelledby="CekAntara-tab">
            <div id="accDataCEK_ANTARA" role="tablist" aria-multiselectable="true">
                <!-- CEK_ANTARA -->
                 <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="30">No.</th>
                            <th class="text-center">Name</th>
                            <th class="text-center" width="150">Last Update</th>
                            <th class="text-center" width="200">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 0;
                        if (isset($ArrDoc[8])):
                            foreach ($ArrDoc[8] as $d1): $n++; ?>
                                <tr>
                                    <td class="align-middle text-center"><?= $n; ?></td>
                                    <td class="align-middle"><?= $d1->name; ?></td>
                                    <td class="align-middle text-center"><?= $d1->created_at; ?></td>
                                    <td class="align-middle text-center">
                                        <a href="<?= base_url() . $file . "CEK_ANTARA/" . $d1->file; ?>" target="_blank" class="btn btn-icon btn-xs btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        <a href="<?= base_url() . $file . "CEK_ANTARA/" . $d1->file; ?>" target="_blank" download="<?= $d1->name; ?>" class="btn py-1 btn-xs btn-success"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="4">
                                    <p class="h5"> Not available data </p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>


</div>