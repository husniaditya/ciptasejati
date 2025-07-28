// Sertifikat Table
function callTable() {
  $('#pusatdata-table').DataTable({
    responsive: true,
    order: [[1, 'asc']],
    dom: 'Bfrtlip',
    scrollX: true,
    scrollY: '350px',
    buttons: ['copy', 'csv', 'excel', 'pdf']
  });
}

$(document).ready(function() {
  callTable();
  // add Sertifikat
  handleForm('#AddPusatdata-form', SuccessNotification, FailedNotification, UpdateNotification);
  // edit Sertifikat
  handleForm('#EditPusatdata-form', UpdateNotification, FailedNotification, UpdateNotification);
});

function reloadDataTable() {
  $.post('module/ajax/master/pusatdata/aj_tablepusatdata.php', function(response) {
    $('#pusatdata-table').DataTable().destroy();
    $("#datapusatdata").html(response);
    callTable();
  });
}

// ----- Start of Sertifikat Section ----- //
function handleForm(formId, successNotification, failedNotification, updateNotification) {
  $(formId).submit(function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    var buttonId = $(event.originalEvent.submitter).attr('id');
    formData.append(buttonId, 'edit');
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/pusatdata/t_pusatdata.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response === 'Success' || response === 'Update') {
          successNotification(response === 'Success' ? 'Data berhasil tersimpan!' : 'Data berhasil terupdate!');
          $(formId.replace("-form", "")).modal('hide');
          filterPusatDataEvent();
        } else {
          failedNotification(response);
        }
      }
    });
  });
}

// Delete Sertifikat
function deletepusatdata(value1, value2) {
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    $.post('module/backend/master/pusatdata/t_pusatdata.php', {
      PUSATDATA_ID: value1,
      EVENT_ACTION: value2
    }, function(response) {
      if (response === 'Success') {
        DeleteNotification('Data berhasil dihapus!');
        reloadDataTable();
      } else {
        FailedNotification(response);
      }
    }).fail(function(xhr) {
      console.error('Request failed. Status: ' + xhr.status);
    });
  }
}

// View Pusatdata
$(document).on("click", ".open-ViewPusatdata", function () {
  var id = $(this).data('pusatid');
  $(".modal-body #viewCABANG_ID").val($(this).data('cabangnama'));
  $(".modal-body #viewPUSATDATA_KATEGORI").val($(this).data('kategori'));
  $(".modal-body #viewPUSATDATA_JUDUL").val($(this).data('judul'));
  $(".modal-body #viewPUSATDATA_DESKRIPSI").val($(this).data('deskripsi'));
  $(".modal-body #viewDELETION_STATUS").val($(this).data('pusatstatus'));
  $.post("module/ajax/master/pusatdata/aj_loadpusatdata.php", { EVENT_ID: id }, function(data) {
    $("#viewpusatfile").html(data);
  });
});

// Edit Pusatdata
$(document).on("click", ".open-EditPusatdata", function () {
  var id = $(this).data('pusatid');
  var cabangid = $(this).data('cabangid');
  $(".modal-body #editPUSATDATA_ID").val(id);
  $(".modal-body #selectize-dropdown2").val(cabangid);
  $(".modal-body #editPUSATDATA_KATEGORI").val($(this).data('kategori'));
  $(".modal-body #editPUSATDATA_JUDUL").val($(this).data('judul'));
  $(".modal-body #editPUSATDATA_DESKRIPSI").val($(this).data('deskripsi'));
  $(".modal-body #editDELETION_STATUS").val($(this).data('status'));
  $.post("module/ajax/master/pusatdata/aj_loadpusatdata.php", { EVENT_ID: id }, function(data) {
    $("#editpusatfile").html(data);
  });
  var selectize = $(".modal-body #selectize-dropdown2")[0].selectize;
  selectize.setValue(cabangid);
});

// Filtering
$('.filterPusatData select, .filterPusatData input').on('change input', debounce(filterPusatDataEvent, 500));
function filterPusatDataEvent() {
  const formData = {
    CABANG_KEY: $('#selectize-select').val(),
    PUSATDATA_KATEGORI: $('#selectize-select2').val(),
    PUSATDATA_JUDUL: $('#filterPUSATDATA_JUDUL').val(),
    PUSATDATA_DESKRIPSI: $('#filterPUSATDATA_DESKRIPSI').val(),
    DELETION_STATUS: $('#filterDELETION_STATUS').val()
  };
  $.post('module/ajax/master/pusatdata/aj_tablepusatdata.php', formData, function(response) {
    $('#pusatdata-table').DataTable().destroy();
    $("#datapusatdata").html(response);
    callTable();
  });
}

// ----- Function to reset form ----- //
function clearForm() {
  // Clear Selectize dropdowns
  var s1 = $('#selectize-select')[0]?.selectize;
  var s2 = $('#selectize-select2')[0]?.selectize;
  if (s1) s1.clear();
  if (s2) s2.clear();
  document.getElementById("filterPusatData").reset();
  reloadDataTable();
}

// ----- End of Pusat Section ----- //