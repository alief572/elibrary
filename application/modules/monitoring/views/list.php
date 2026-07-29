<div class="content d-flex flex-column flex-column-fluid p-0">
  <div class="container mt-3">
    <div class="mb-5">
      <div style="font-size: 36px;" class="text-white font-weight-bolder">
        <a style="font-size: 30px;" href="<?= base_url($this->uri->segment(1)); ?>" class="text-warning" title="Back to Monitoring">
          <span class="fa fa-chevron-left"></span>
        </a>
        <?= $title; ?>
      </div>
    </div>
    <div class="input-group mb-3 w-100 w-md-25" style="max-width: 350px;">
      <span class="input-group-text rounded-right-0"><i class="fa fa-search"></i></span>
      <input type="text" name="search" id="search" class="form-control" placeholder="Search">
    </div>
    <div class="card">
      <div class="pt-1 px-3 card-body">
        <!-- PROCEDURES -->
        <div class="table-responsive">
          <table class="table table-hover datatable w-100">
          <thead>
            <tr class="table-light">
              <th width="30px">No</th>
              <th width="120px">Nomor</th>
              <th>File Name</th>
              <th width="150px">Kelompok Proses</th>
              <th width="80px" class="text-center">Revisi</th>
              <th width="150px" class="text-center">Authority</th>
              <!-- <th width="180px" class="text-center">Created Date</th> -->
              <!-- <th width="150px" class="text-center">Created By</th> -->
              <th width="100px" class="text-center">Status</th>
              <th width="110px" class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $n = 0;
            if ($procedures) :
              foreach ($procedures as $list) : $n++; ?>
                <tr>
                  <td style="vertical-align: middle;" class="text-center"><?= $n; ?></td>
                  <td style="vertical-align: middle;"><?= (!empty($list->nomor)) ? $list->nomor : ((isset($list->number) && !empty($list->number)) ? $list->number : '-'); ?></td>
                  <td class="text-dark-75" style="vertical-align: middle; min-width: 180px; max-width: 280px;">
                    <div class="d-flex justify-content-start align-items-center">
                      <i class='text-success fa fa-file-alt mr-2 fa-2x py-0 flex-shrink-0' style='vertical-align:middle;'></i>
                      <span class="h6 mb-0 font-weight-bold text-line-clamp-2" title="<?= htmlspecialchars($list->name); ?>"><?= $list->name; ?></span>
                    </div>
                  </td>
                  <td style="vertical-align: middle;"><?= (isset($ArrGroup[$list->group_procedure])) ? $ArrGroup[$list->group_procedure] : '-'; ?></td>
                  <td style="vertical-align: middle;" class="text-center">Rev. <?= $list->revision; ?></td>
                  <td class="text-center" style="vertical-align: middle;">
                    <?php if ($list->status == 'REV' && isset($ArrPosition[$list->reviewer_id])) : ?>
                      <?= $ArrPosition[$list->reviewer_id]; ?>
                    <?php elseif ($list->status == 'APV' && isset($ArrPosition[$list->approval_id])) : ?>
                      <?= $ArrPosition[$list->approval_id]; ?>
                    <?php elseif ($list->status == 'PUB') : ?>
                      <?= isset($ArrPosition[$list->approval_id]) ? $ArrPosition[$list->approval_id] : (isset($ArrUsers[$list->approved_by]) ? $ArrUsers[$list->approved_by]->full_name : (isset($ArrUsers[$list->prepared_by]) ? $ArrUsers[$list->prepared_by]->full_name : '-')); ?>
                    <?php elseif (($list->status == 'RVI' || $list->status == 'COR') && isset($ArrUsers[$list->prepared_by])) : ?>
                      <?= $ArrUsers[$list->prepared_by]->full_name; ?>
                    <?php else : ?>
                      <?= isset($ArrPosition[$list->approval_id]) ? $ArrPosition[$list->approval_id] : (isset($ArrUsers[$list->prepared_by]) ? $ArrUsers[$list->prepared_by]->full_name : '-'); ?>
                    <?php endif; ?>
                  </td>
                  <td class="text-center" style="vertical-align: middle;">
                    <?= $sts[$list->status] ?>
                  </td>
                  <td class="text-center">
                    <button type="button" data-id="<?= $list->id; ?>" data-type="procedures" class="btn btn-primary btn-icon view-data btn-xs shadow-sm"><i class="fa fa-eye"></i></button>
                    <?php if (isset($ArrPosts)) : ?>
                      <?php if ($list->status == 'REV') : ?>
                        <?php if (in_array($list->reviewer_id, $ArrPosts)) : ?>
                          <button type="button" data-id="<?= $list->id; ?>" data-type="procedures" class="btn btn-warning btn-icon review btn-xs shadow-sm"><i class="fa fa-cog"></i></button>
                        <?php endif; ?>
                      <?php elseif ($list->status == 'HLD' && $list->deletion_status == 'OPN') : ?>
                        <?php if (in_array($list->reviewer_id, $ArrPosts)) : ?>
                          <button type="button" data-id="<?= $list->id; ?>" data-type="procedures" class="btn btn-warning btn-icon review-del btn-xs shadow-sm"><i class="fa fa-cog"></i></button>
                        <?php endif; ?>
                      <?php elseif ($list->status == 'HLD' && $list->deletion_status == 'REV') : ?>
                        <?php if (in_array($list->reviewer_id, $ArrPosts)) : ?>
                          <button type="button" data-id="<?= $list->id; ?>" data-type="procedures" class="btn btn-info btn-icon approval-del btn-xs shadow-sm"><i class="fa fa-cog"></i></button>
                        <?php endif; ?>
                      <?php elseif ($list->status == 'APV') : ?>
                        <?php if (in_array($list->approval_id, $ArrPosts)) : ?>
                          <button type="button" data-id="<?= $list->id; ?>" data-type="procedures" class="btn btn-info btn-icon approve btn-xs shadow-sm"><i class="fa fa-cog"></i></button>
                        <?php endif; ?>
                      <?php elseif ($list->status == 'COR') : ?>
                        <?php if (in_array($list->prepared_by, $ArrPosts)) : ?>
                          <button type="button" data-id="<?= $list->id; ?>" data-type="procedures" class="btn btn-info btn-icon approve btn-xs shadow-sm"><i class="fa fa-cog"></i></button>
                        <?php endif; ?>
                      <?php elseif ($list->status == 'PUB') : ?>
                        <button type="button" data-id="<?= $list->id; ?>" data-type="procedures" class="btn btn-warning btn-icon revision btn-xs shadow-sm" title="Submit Revision"><i class="fa fa-edit"></i></button>
                        <button type="button" data-id="<?= $list->id; ?>" data-type="procedures" class="btn btn-danger btn-icon deletion btn-xs shadow-sm" title="Submit Deletion"><i class="fa fa-trash"></i></button>
                      <?php else : ?>
                      <?php endif; ?>
                    <?php endif; ?>
                  </td>
                </tr>
            <?php endforeach;
            endif; ?>
          </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="Modal" data-backdrop="static" data-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content overflow-hidden" style="height:85vh; max-height:850px;">
      <form class="form-horiontal h-100 d-flex flex-column" id="form-review">
        <div id="content-modal" class="h-100 d-flex flex-column overflow-hidden"></div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="Modal2" data-backdrop="static" data-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 1070;">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg border-0">
      <div class="modal-header py-2 px-3 bg-light border-bottom d-flex justify-content-between align-items-center">
        <h5 class="modal-title font-weight-bold text-dark mb-0" id="Modal2Title"><i class="fa fa-paper-plane text-primary mr-2"></i> Form Permohonan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="form-horizontal mb-0" id="form-revision">
        <div id="content-modal2" class="p-3"></div>
      </form>
    </div>
  </div>
</div>

<!-- SUB MODAL UNTUK DOKUMEN TERKAIT (FORM & IK) -->
<div class="modal fade" id="ModalSub" data-backdrop="static" data-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg border-0" style="height: 80vh; max-height: 750px;">
      <div class="modal-header py-2 px-3 bg-light border-bottom d-flex justify-content-between align-items-center">
        <h5 class="modal-title font-weight-bold text-dark mb-0" id="ModalSubTitle"><i class="fa fa-file-alt text-primary mr-2"></i> Detail Dokumen Terkait</h5>
        <button type="button" class="close" onclick="$('#ModalSub').modal('hide');" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-3 overflow-auto" id="content-modal-sub">
      </div>
      <div class="modal-footer py-2 px-3 bg-light border-top text-right">
        <button type="button" class="btn btn-secondary btn-sm px-4" onclick="$('#ModalSub').modal('hide');">
          <i class="fa fa-times mr-1"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

<style>
  p {
    margin-bottom: 0px;
  }

  .dataTables_filter {
    display: none;
  }

  .text-line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
    max-height: 2.6em;
    word-break: break-word;
  }

  @media (max-width: 767.98px) {
    .modal-dialog.modal-xl {
      margin: 0.5rem;
      max-width: calc(100% - 1rem) !important;
    }
    .modal-content {
      height: 92vh !important;
    }
  }
</style>

<script>
  $(document).ready(function() {
    // Handle stacked modals z-index and body scroll state
    $(document).on('show.bs.modal', '.modal', function() {
      const zIndex = 1050 + (10 * $('.modal:visible').length);
      $(this).css('z-index', zIndex);
      setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
      }, 0);
    });

    $(document).on('hidden.bs.modal', '.modal', function() {
      if ($('.modal:visible').length > 0) {
        setTimeout(function() {
          $(document.body).addClass('modal-open');
        }, 0);
      }
    });

    table = $('.datatable').DataTable({
      lengthChange: false
    })

    /* SELECT one */
    $(document).on('change', '.status', function() {
      if ($(this).is(':checked')) {
        $('.status').prop('checked', false)
        $(this).prop('checked', true)
      }
    })

    // #column3_search is a <input type="text"> element
    $('#search').on('paste input', function() {
      table
        .columns(1)
        .search(this.value)
        .draw();
    });

    $(document).on('click', '.review', function() {
      const id = $(this).data('id')
      const type = $(this).data('type')
      $('#Modal').modal('show')
      $('#content-modal').load(siteurl + active_controller + 'load_form_review/' + id + "/" + type)
    })

    $(document).on('click', '.approve', function() {
      const id = $(this).data('id')
      const type = $(this).data('type')
      $('#Modal').modal('show')
      $('#content-modal').load(siteurl + active_controller + 'load_form_approval/' + id + "/" + type)
    })

    $(document).on('click', '.correction', function() {
      const id = $(this).data('id')
      const type = $(this).data('type')
      $('#Modal').modal('show')
      $('#content-modal').load(siteurl + active_controller + 'load_form_correction/' + id + "/" + type)
    })

    $(document).on('click', '.revision', function() {
      const id = $(this).data('id')
      const type = $(this).data('type')
      $('#Modal2Title').html('<i class="fa fa-edit text-warning mr-2"></i> Form Submit Revision')
      $('#Modal2').modal('show')
      $('#content-modal2').load(siteurl + active_controller + 'load_form_revision/' + id + "/" + type)
    })

    $(document).on('click', '.deletion', function() {
      const id = $(this).data('id')
      const type = $(this).data('type')
      $('#Modal2Title').html('<i class="fa fa-trash text-danger mr-2"></i> Form Submit Deletion')
      $('#Modal2').modal('show')
      $('#content-modal2').load(siteurl + active_controller + 'load_form_deletion/' + id + "/" + type)
    })

    $(document).on('click', '.view', function() {
      const id = $(this).data('id')
      const type = $(this).data('type')
      $('#Modal').modal('show')
      $('#content-modal').load(siteurl + active_controller + 'view/' + id + "/" + type)
    })

    $(document).on('click', '.view-data', function() {
      const id = $(this).data('id')
      const type = $(this).data('type')
      $('#Modal').modal('show')
      $('#content-modal').load(siteurl + active_controller + 'view_data/' + id + "/" + type)
    })

    $(document).on('click', '#save-review', function() {
      $('#invalid-action').addClass('d-none')
      $('#note').removeClass('is-invalid')

      const id = $('#id').val();
      const status = $('input[name="status"]').is(':checked');
      const note = $('#note').val();
      const btn = $(this)
      if (status == '' || status == null) {
        $('#invalid-action').removeClass('d-none')
        return false;
      }
      if ((note == '' && status == 'COR') || (note == null && status == 'COR')) {
        $('#note').addClass('is-invalid')
        return false;
      }

      Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to process again this data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Process it!",
        cancelButtonText: "No, cancel process!",
      }).then((value) => {
        if (value.isConfirmed) {
          var formData = new FormData($('#form-review')[0]);
          var baseurl = siteurl + active_controller + 'save_review';
          $.ajax({
            url: baseurl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
              btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...')
            },
            complete: function() {
              btn.prop('disabled', false).html('<span class="fa fa-send" role="status" aria-hidden="true"></span> Submit Review')
            },
            success: function(data) {
              if (data.status == 1) {
                Swal.fire({
                  title: "Success!",
                  text: data.msg,
                  icon: "success",
                  timer: 1500,
                  showCancelButton: false,
                  showConfirmButton: false,
                  allowOutsideClick: false
                }).then(() => {
                  location.reload()
                  $('#Modal').modal('hide')
                  // $('#content-modal').html('')
                });
              } else {
                if (data.status == 0) {
                  Swal.fire({
                    title: "Failed!",
                    html: data.msg,
                    icon: "warning",
                    timer: 3000,
                  });
                }
              }
            },
            error: function() {
              Swal.fire({
                title: "Error Message !",
                text: 'An Error Occured During Process. Please try again..',
                icon: "warning",
                timer: 3000,
              });
            }
          });
        }
      });
    });

    $(document).on('click', '#save-approval', function() {
      $('#invalid-action').addClass('d-none')
      $('#note').removeClass('is-invalid')

      const id = $('#id').val();
      const status = $('input[name="status"]').is(':checked');
      const note = $('#note').val();
      const btn = $(this)
      if (status == '' || status == null) {
        $('#invalid-action').removeClass('d-none')
        return false;
      }
      if ((note == '' && status == 'COR') || (note == null && status == 'COR')) {
        $('#note').addClass('is-invalid')
        return false;
      }

      Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to process again this data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Process it!",
        cancelButtonText: "No, cancel process!",
      }).then((value) => {
        if (value.isConfirmed) {
          var formData = new FormData($('#form-review')[0]);
          var baseurl = siteurl + active_controller + 'save_approval';
          $.ajax({
            url: baseurl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
              btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...')
            },
            complete: function() {
              btn.prop('disabled', false).html('<span class="fa fa-send" role="status" aria-hidden="true"></span> Submit Review')
            },
            success: function(data) {
              if (data.status == 1) {
                Swal.fire({
                  title: "Success!",
                  text: data.msg,
                  icon: "success",
                  timer: 1500,
                  showCancelButton: false,
                  showConfirmButton: false,
                  allowOutsideClick: false
                }).then(() => {
                  location.reload()
                  $('#Modal').modal('hide')
                  // $('#content-modal').html('')
                });
              } else {
                if (data.status == 0) {
                  Swal.fire({
                    title: "Failed!",
                    html: data.msg,
                    icon: "warning",
                    timer: 3000,
                  });
                }
              }
            },
            error: function() {
              Swal.fire({
                title: "Error Message !",
                text: 'An Error Occured During Process. Please try again..',
                icon: "warning",
                timer: 3000,
              });
            }
          });
        }
      });
    });

    $(document).on('click', '.save-revision', function() {
      $('#invalid-action').addClass('d-none')
      $('#note').removeClass('is-invalid')

      const id = $('#id').val();
      const reason = $('#note').val();
      const btn = $(this)
      const btn_text = $(this).html()

      if ((reason == '') || (reason == null)) {
        $('#note').addClass('is-invalid')
        return false;
      }

      Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to process again this data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Process it!",
        cancelButtonText: "No, cancel process!",
      }).then((value) => {
        if (value.isConfirmed) {
          var formData = new FormData($('#form-revision')[0]);
          var baseurl = siteurl + active_controller + 'save_revision';
          $.ajax({
            url: baseurl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
              btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...')
            },
            complete: function() {
              console.log(btn);
              btn.prop('disabled', false).html(btn_text)
            },
            success: function(data) {
              if (data.status == 1) {
                Swal.fire({
                  title: "Success!",
                  text: data.msg,
                  icon: "success",
                  timer: 1500,
                  showCancelButton: false,
                  showConfirmButton: false,
                  allowOutsideClick: false
                }).then(() => {
                  location.reload()
                  $('#Modal').modal('hide')
                  // $('#content-modal').html('')
                });
              } else {
                if (data.status == 0) {
                  Swal.fire({
                    title: "Failed!",
                    html: data.msg,
                    icon: "warning",
                    timer: 3000,
                  });
                }
              }
            },
            error: function() {
              Swal.fire({
                title: "Error Message !",
                text: 'An Error Occured During Process. Please try again..',
                icon: "error",
                timer: 3000,
              });
            }
          });
        }
      });
    });

    $(document).on('click', '.save-deletion', function() {
      $('#invalid-action').addClass('d-none')
      $('#note').removeClass('is-invalid')

      const id = $('#id').val();
      const reason = $('#note').val();
      const btn = $(this)
      const btn_text = $(this).html()

      if ((reason == '') || (reason == null)) {
        $('#note').addClass('is-invalid')
        return false;
      }

      Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to process again this data!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Process it!",
        cancelButtonText: "No, cancel process!",
      }).then((value) => {
        if (value.isConfirmed) {
          var formData = new FormData($('#form-revision')[0]);
          var baseurl = siteurl + active_controller + 'save_deletion';
          $.ajax({
            url: baseurl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: 'json',
            beforeSend: function() {
              btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...')
            },
            complete: function() {
              console.log(btn);
              btn.prop('disabled', false).html(btn_text)
            },
            success: function(data) {
              if (data.status == 1) {
                Swal.fire({
                  title: "Success!",
                  text: data.msg,
                  icon: "success",
                  timer: 1500,
                  showCancelButton: false,
                  showConfirmButton: false,
                  allowOutsideClick: false
                }).then(() => {
                  location.reload()
                  $('#Modal').modal('hide')
                  // $('#content-modal').html('')
                });
              } else {
                if (data.status == 0) {
                  Swal.fire({
                    title: "Failed!",
                    html: data.msg,
                    icon: "warning",
                    timer: 3000,
                  });
                }
              }
            },
            error: function() {
              Swal.fire({
                title: "Error Message !",
                text: 'An Error Occured During Process. Please try again..',
                icon: "error",
                timer: 3000,
              });
            }
          });
        }
      });
    });

    $(document).on('click', '.review-del', function() {
      const id = $(this).data('id')
      const type = $(this).data('type')
      let sts
      Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to process again this data!",
        icon: "warning",
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: "Yes, I Agree",
        cancelButtonText: "Cancel",
        denyButtonText: "Reject",
      }).then((value) => {
        if (value.isConfirmed || value.isDenied) {
          var baseurl = siteurl + active_controller + 'save_rev_deletion';
          if (value.isConfirmed) {
            var sts = 'REV'
          } else if (value.isDenied) {
            var sts = 'REJ'
          }

          $.ajax({
            url: baseurl,
            type: "POST",
            data: {
              id,
              sts
            },
            dataType: 'json',
            success: function(data) {
              if (data.status == 1) {
                Swal.fire({
                  title: "Success!",
                  text: data.msg,
                  icon: "success",
                  timer: 1500,
                  showCancelButton: false,
                  showConfirmButton: false,
                  allowOutsideClick: false
                }).then(() => {
                  location.reload()
                  $('#Modal').modal('hide')
                  // $('#content-modal').html('')
                });
              } else {
                if (data.status == 0) {
                  Swal.fire({
                    title: "Failed!",
                    html: data.msg,
                    icon: "warning",
                    timer: 3000,
                  });
                }
              }
            },
            error: function() {
              Swal.fire({
                title: "Error Message !",
                text: 'An Error Occured During Process. Please try again..',
                icon: "error",
                timer: 3000,
              });
            }
          });
        }
      })

    });

    $(document).on('click', '.approval-del', function() {
      const id = $(this).data('id')
      const type = $(this).data('type')
      let sts
      Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to process again this data!",
        icon: "warning",
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: "Yes, I Agree",
        cancelButtonText: "Cancel",
        denyButtonText: "Reject",
      }).then((value) => {
        if (value.isConfirmed || value.isDenied) {
          var baseurl = siteurl + active_controller + 'save_apv_deletion';
          if (value.isConfirmed) {
            var sts = 'APV'
          } else if (value.isDenied) {
            var sts = 'REJ'
          }

          $.ajax({
            url: baseurl,
            type: "POST",
            data: {
              id,
              sts
            },
            dataType: 'json',
            success: function(data) {
              if (data.status == 1) {
                Swal.fire({
                  title: "Success!",
                  text: data.msg,
                  icon: "success",
                  timer: 1500,
                  showCancelButton: false,
                  showConfirmButton: false,
                  allowOutsideClick: false
                }).then(() => {
                  location.reload()
                  $('#Modal').modal('hide')
                  // $('#content-modal').html('')
                });
              } else {
                if (data.status == 0) {
                  Swal.fire({
                    title: "Failed!",
                    html: data.msg,
                    icon: "warning",
                    timer: 3000,
                  });
                }
              }
            },
            error: function() {
              Swal.fire({
                title: "Error Message !",
                text: 'An Error Occured During Process. Please try again..',
                icon: "error",
                timer: 3000,
              });
            }
          });
        }
      })

    });

    $(document).on('click', '.view-form', function(e) {
      e.preventDefault();
      const id = $(this).data('id');
      if (id) {
        $('#ModalSubTitle').html('<i class="fa fa-file-alt text-success mr-2"></i> Detail Form Dokumen');
        $('#content-modal-sub').html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2 text-muted">Loading detail form...</p></div>');
        $('#ModalSub').modal('show');
        $('#content-modal-sub').load(siteurl + active_controller + 'view_form/' + id);
      } else {
        Swal.fire('Warning!', 'Data form tidak tersedia', 'warning');
      }
    });

    $(document).on('click', '.view-guide', function(e) {
      e.preventDefault();
      const id = $(this).data('id');
      if (id) {
        $('#ModalSubTitle').html('<i class="fa fa-book text-danger mr-2"></i> Detail Instruksi Kerja (IK)');
        $('#content-modal-sub').html('<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2 text-muted">Loading detail IK...</p></div>');
        $('#ModalSub').modal('show');
        $('#content-modal-sub').load(siteurl + active_controller + 'view_guide/' + id);
      } else {
        Swal.fire('Warning!', 'Data IK tidak tersedia', 'warning');
      }
    });

    $('#ModalSub').on('hidden.bs.modal', function () {
      if ($('#Modal').is(':visible')) {
        $('body').addClass('modal-open');
      }
    });

  })
</script>