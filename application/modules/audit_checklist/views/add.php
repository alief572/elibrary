<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
  <div class="d-flex flex-column-fluid">
    <div class="container">
      <div class="card card-stretch shadow card-custom">
        <div class="card-header">
          <h2 class="mt-5"><i class="<?= $icon; ?> mr-2"></i><?= $title; ?></h2>
          <div class="mt-4 float-right ">
            <a href="<?= base_url($this->uri->segment(1)); ?>" class="btn btn-danger"><i class="fa fa-reply"></i>Back</a>
          </div>
        </div>
        <div class="card-body">
          <form id="form-checklist">
            <div class="row mb-3">
              <label for="exampleInputEmail1" class="col-2 col-form-label">Select Procedures</label>
              <div class="col-3">
                <select name="procedure_id" id="procedure_id" class="form-control form-control-solid required select2" data-dropdown-parent=".card-body">
                  <option value=""></option>
                  <?php foreach ($data as $dt) : ?>
                    <option value="<?= $dt->id; ?>"><?= $dt->name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <hr>
            <div class="load_data mb-10"></div>

            <!-- CHECKLIT TEMUAN -->
            <h4 class="card-title mb-3"><i class="fa fa-check-circle text-primary" aria-hidden="true"></i> Checklist</h4>
            <table id="tblChecklist" class="table table-sm table-bordered table-condensed table-hover">
              <thead class="table-light">
                <tr>
                  <th width="30">No</th>
                  <th>Checklist</th>
                  <th width="50">Action</th>
                </tr>
              </thead>
              <tbody class="text-center"></tbody>
            </table>
            <div class="text-center">
              <button type="button" class="btn btn-primary" id="add-checklist"><i class="fa fa-plus" aria-hidden="true"></i>Add Checklist</button>
              <span id="btn-save"></span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalView" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
        <span class="btn-close cursor-pointer" data-dismiss="modal" aria-label="Close">
          <div class="fa fa-times"></div>
        </span>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {
    $('.select2').select2({
      placeholder: "Choose an options",
      width: "100%",
      allowClear: true
    });

    $(document).on('change', '#procedure_id', function() {
      let id = $(this).val();
      $('.load_data').html('')
      if (id) {
        $.ajax({
          url: siteurl + active_controller + 'select_procedure/' + id,
          type: 'GET',
          success: function(result) {
            if (result) {
              $('.load_data').html(result)
            } else {
              Swal.fire('Warning', "Can't show data. Please try again!", 'warning', 2000)
            }
          },
          error: function() {
            Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 3000)
          }
        })
      }
    })

    $(document).on('click', '#add-checklist', function() {
      let n = $('#tblChecklist tbody tr').length + 1
      let html = `
      <tr>
        <td>${n}</td>
        <td>
          <textarea name="checklist[${n}][description]" class="form-control required" placeholder="Description"></textarea>
        </td>
        <td><button type="button" class="btn btn-xs btn-icon btn-danger remove"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
      </tr>
      `
      $('#tblChecklist tbody').append(html)
      checkStd()
    })


    $(document).on('click', '.remove', function() {
      let id = $(this).data('id')

      if (id) {

      } else {
        $(this).parents('tr').remove()
      }
      renumberList()
      checkStd()

    })

    $(document).on('click', '.read', function() {
      let id = $(this).data('id')
      $.ajax({
        url: siteurl + active_controller + 'view_pasal/' + id,
        type: 'GET',
        dataType: 'JSON',
        success: function(result) {
          if (result) {
            let html = `
						<div class="form-group">
							<label class="font-weight-bold"><strong>Pasal</strong></label>
							<div class="">
							` + result.chapter + `
							</div>
						</div>

						<!-- Nav tabs -->
						<ul class="nav nav-fill nav-pills" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="nav-link nav-pill active" id="indo-tab" data-toggle="tab" data-target="#indo" type="button" role="tab" aria-controls="indo" aria-selected="true">Indonesian</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link nav-pill" id="eng-tab" data-toggle="tab" data-target="#eng" type="button" role="tab" aria-controls="eng" aria-selected="false">English</a>
							</li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content mt-4 border rounded-lg p-5">
							<div class="tab-pane active pt-4 pb-4" id="indo" role="tabpanel" aria-labelledby="indo-tab">
							` + result.desc_indo + `
							</div>
							<div class="tab-pane pt-4 pb-4" id="eng" role="tabpanel" aria-labelledby="eng-tab">
							` + result.desc_eng + `
							</div>
						</div>
						`;
            $('.modal-body').html(html);
            $('#modalView').modal('show')
          } else {
            Swal.fire('Warning', 'Data not valid. Please try again!', 'warning', 3000)
          }

        },
        error: function() {
          Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 5000)
        }
      })
    })


    $(document).on('submit', '#form-checklist', function(e) {
      e.preventDefault();
      let btn = $('#save')

      let valid = getValidation('#form-checklist')
      let formdata = new FormData($(this)[0])

      if (valid) {
        Swal.fire({
          title: 'Confirmation!',
          icon: 'question',
          text: 'Are you sure to save this data?',
          showCancelButton: true,
        }).then((value) => {
          if (value.isConfirmed) {
            $.ajax({
              url: siteurl + active_controller + 'save',
              data: formdata,
              type: 'POST',
              dataType: 'JSON',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function() {
                btn.attr('disabled', true).html('<i class="spinner spinner-border-sm mr-2"></i> Loading...')
              },
              complete: function() {
                btn.attr('disabled', false).html('<i class="fa fa-save"></i>Save')
              },
              success: function(result) {
                if (result.status == 1) {
                  Swal.fire({
                    title: 'Success!',
                    icon: 'success',
                    text: result.msg,
                    timer: 3000
                  }).then(function() {
                    location.href = siteurl + active_controller;
                  })
                } else {
                  Swal.fire({
                    title: 'Warning!',
                    icon: 'warning',
                    text: result.msg,
                    timer: 3000
                  })
                }
              },
              error: function(result) {
                Swal.fire({
                  title: 'Error!',
                  icon: 'error',
                  text: 'Server timeout, becuase error!',
                  timer: 3000
                })
              }
            })
          }
        })
      }
    })
  })

  function renumberList() {
    let n = 0
    $('#tblChecklist tbody tr').each(function() {
      n++;
      $(this).find('td:first').text(n)
      $(this).find('input[name^="checklist"]').attr('name', "checklist[" + n + "][id]")
      $(this).find('textarea[name^="checklist"]').attr('name', "checklist[" + n + "][description]")
    })
  }

  function checkStd() {
    const btnSave = `<button type="submit" class="btn btn-info text-center min-w-100px" id="save"><i class="fa fa-save"></i>Save</button>`
    const input = $('#tblChecklist tbody textarea').length

    if (input > 0) {
      $('#btn-save').html(btnSave)
    } else {
      $('#btn-save').html('')
    }
  }
</script>