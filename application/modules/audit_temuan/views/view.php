 <div class="row">
   <div class="col-md-6">
     <div class="mb-2 row">
       <label for="" class="col-md-4 h6 font-weight-bold">Company</label>
       <label for="" class="col h6">: <?= $dataAudit->company_name; ?></label>
     </div>
     <div class="mb-2 row">
       <label for="" class="col-md-4 h6 font-weight-bold">Badan Sertifikasi</label>
       <label for="" class="col h6">: <?= $dataAudit->name; ?></label>
     </div>
   </div>
 </div>
 <hr>
 <div class="form-group h4 row">
   <span for="" class="col-md-2 font-weight-bolder">Standard</span>
   <span for="" class="col font-weight-bolder">: <?= $dataStd->standard_name; ?></span>
 </div>

 <label for="" class="contol-label font-weight-bolder h6 mb-4">
   <i class="fa fa-clipboard-list text-dark" aria-hidden="true"></i> Detail Temuan</label>
 <div class="mb-2">
   <table id="dtTable" class="table table-sm dataTable display table-bordered table-condensed table-hover">
     <thead class="table-light">
       <tr class="text-center">
         <th width="10">No</th>
         <th>Pasal</th>
         <th>Temuan</th>
         <th>Kategori</th>
         <th>Proses</th>
         <th>Auditee</th>
         <th>Auditor</th>
         <th width="50">Auditor Internal</th>
         <th>Konsultan</th>
       </tr>
     </thead>
     <tbody>
       <?php
        $cat = [
          '1' => '<span class="label label-success label-inline">Minor</span>',
          '2' => '<span class="label label-danger label-inline">Major</span>',
          '3' => '<span class="label label-warning label-inline">OFI</span>',
        ];

        if ($details) foreach ($details as $k => $v) : $k++; ?>
         <tr class="text-center">
           <td><?= $k; ?></td>
           <td class="text-left">
             <?php if ($v->pasal_name) : ?>
               <li><?= implode("</li><li>", json_decode($v->pasal_name)); ?></li>
             <?php endif; ?>
           </td>
           <td class="text-left"><?= $v->description; ?></td>
           <td><?= $cat[$v->category]; ?></td>
           <td><?= $v->process_name; ?></td>
           <td><?= $v->auditee; ?></td>
           <td><?= $v->auditor_name; ?></td>
           <td><?= $v->auditor_internal_name; ?></td>
           <td><?= $v->audit_consultant_name; ?></td>
         </tr>
       <?php endforeach; ?>
     </tbody>
   </table>
 </div>

 <script>
   $(document).ready(function() {
     $('#dtTable').DataTable({
       destroy: true,
       autoWidth: false
     })
   })
 </script>