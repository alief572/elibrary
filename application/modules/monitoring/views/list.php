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
    <div class="input-group mb-3 w-25">
      <span class="input-group-text rounded-right-0 "><i class="fa fa-search"></i></span>
      <input type="text" name="search" id="search" class="form-control w-300" placeholder="Search">
    </div>
    <div class="card">
      <div class="pt-1 px-3 card-body">
        <!-- PRODEDURES -->
        <table class="table table-hover datatable">
          <thead>
            <tr class="table-light">
              <th width="40px">No</th>
              <th>File Name</th>
              <th class="text-center">Authority</th>
              <!-- <th width="180px" class="text-center">Created Date</th> -->
              <!-- <th width="150px" class="text-center">Created By</th> -->
              <th width="150px" class="text-center">Status</th>
              <th width="100px" class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $n = 0;
            if ($procedures) :
              foreach ($procedures as $list) : $n++; ?>
                <tr>
                  <td style="vertical-align: middle;" class="text-center"><?= $n; ?></td>
                  <td class="text-dark-75" style="vertical-align: middle;">
                    <div class="d-flex justify-content-start">
                      <i class='text-success fa fa-file-alt mr-2 fa-2x py-0' style='vertical-align:middle;'></i>
                      <span class="h5 mb-0 pt-2"><?= $list->name; ?></span>
                    </div>
                  </td>
                  <td class="text-center" style="vertical-align: middle;">
                    <?php if ($list->status == 'REV') : ?>
                      <?= $ArrPosition[$list->reviewer_id]; ?>
                    <?php elseif ($list->status == 'APV') : ?>
                      <?= $ArrPosition[$list->approval_id]; ?>
                    <?php elseif ($list->status == 'RVI' || $list->status == 'COR') : ?>
                      <?= $ArrUsers[$list->prepared_by]->full_name;; ?>
                    <?php endif; ?>
                  </td>
                  <!-- <td class="text-center" style="vertical-align: middle;"><?= $list->created_at; ?></td> -->
                  <!-- <td class="text-center" style="vertical-align: middle;" class="mt-1"><?= $ArrUsers[$list->created_by]->full_name; ?></td> -->
                  <td class="text-center" style="vertical-align: middle;" class="mt-1">
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
                        <button type="button" data-id="<?= $list->id; ?>" data-type="procedures" class="btn btn-success btn-icon view btn-xs shadow-sm"><i class="fa fa-eye"></i></button>
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

<div class="modal fade" id="Modal" data-backdrop="static" data-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" data-scroll="true" style="height:650px;">
      <form class="form-horiontal" id="form-review">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
          <span type="button" class="close" data-dismiss="modal" aria-label="Close">
            <div class="fa fa-times"></div>
          </span>
        </div>
        <div class="modal-body overflow-auto">
          <div id="content-modal"></div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="Modal2" data-backdrop="static" data-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content" data-scroll="true" style="height:650px;">
      <form class="form-horiontal" id="form-revision">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <span type="button" class="close" data-dismiss="modal" aria-label="Close">
            <div class="fa fa-times"></div>
          </span>
        </div>
        <div class="modal-body overflow-auto">
          <div id="content-modal2"></div>
        </div>
      </form>
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
</style>

<script>
  $(document).ready(function() {
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
      $('#Modal2').modal('show')
      $('#content-modal2').load(siteurl + active_controller + 'load_form_revision/' + id + "/" + type)
    })

    $(document).on('click', '.deletion', function() {
      const id = $(this).data('id')
      const type = $(this).data('type')
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

  })
</script>