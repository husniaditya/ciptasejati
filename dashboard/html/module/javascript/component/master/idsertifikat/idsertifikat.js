// Sertifikat Table
function callTable() {
  $('#idsertifikat-table').DataTable({
    responsive: true,
    order: [],
    dom: 'Bfrtlip',
    // columnDefs: [
    //   { width: '100px', targets: 0 },
    //   { width: '250px', targets: 2 },
    //   { width: '350px', targets: 3 },
    //   { width: '250px', targets: 4 },
    //   { width: '250px', targets: 5 },
    //   { width: '150px', targets: 6 },
    //   { width: '150px', targets: 7 },
    //   { width: '250px', targets: 8 }
    // ],
    scrollX: true,
    scrollY: '350px',
    buttons: ['copy', 'csv', 'excel', 'pdf']
  });
}

function reloadDataTable() {
  $.post('module/ajax/master/idsertifikat/aj_tablesertifikat.php', response => {
    $('#idsertifikat-table').DataTable().destroy();
    $("#idsertifikatdata").html(response);
    callTable();
  });
}

$(document).ready(function() {
  callTable();
  handleForm('#AddSertifikat-form', SuccessNotification, FailedNotification);
  handleForm('#EditSertifikat-form', UpdateNotification, FailedNotification);
});

function handleForm(formId, successNotification, failedNotification) {
  $(formId).submit(function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    var buttonId = $(event.originalEvent.submitter).attr('id');
    formData.append(buttonId, 'edit');
    $.ajax({
      type: 'POST',
      url: 'module/backend/master/idsertifikat/t_idsertifikat.php',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response === 'Success') {
          successNotification('Data berhasil tersimpan!');
          $(formId.replace("-form", "")).modal('hide');
          reloadDataTable();
        } else {
          failedNotification(response);
        }
      }
    });
  });
}

function deletesertifikat(value1, value2) {
  if (confirm("Apakah anda yakin untuk menghapus data ini?")) {
    $.post('module/backend/master/idsertifikat/t_idsertifikat.php', {
      IDSERTIFIKAT_ID: value1,
      EVENT_ACTION: value2
    }, function(response) {
      if (response === 'Success') {
        DeleteNotification('Data berhasil dihapus!');
        reloadDataTable();
      } else {
        FailedNotification(response);
      }
    }).fail(xhr => console.error('Request failed. Status: ' + xhr.status));
  }
}

$(document).on("click", ".open-ViewSertifikat", function () {
  var id = $(this).data('id');
  $(".modal-body #viewTINGKATAN_ID").val($(this).data('tingkatannama'));
  $(".modal-body #viewIDSERTIFIKAT_DESKRIPSI").val($(this).data('desk'));
  $(".modal-body #viewDELETION_STATUS").val($(this).data('sertifikatstatus'));
  $.post("module/ajax/master/idsertifikat/aj_loadidcardfront.php", { EVENT_ID: id }, data => {
    $("#loadviewidfront").html(data);
  });
  $.post("module/ajax/master/idsertifikat/aj_loadidcardback.php", { EVENT_ID: id }, data => {
    $("#loadviewidback").html(data);
  });
  $.post("module/ajax/master/idsertifikat/aj_loadsertifikat.php", { EVENT_ID: id }, data => {
    $("#loadviewsertifikat").html(data);
  });
});

$(document).on("click", ".open-EditSertifikat", function () {
  var id = $(this).data('id');
  $(".modal-body #editIDSERTIFIKAT_ID").val(id);
  $(".modal-body #selectize-dropdown2")[0].selectize.setValue($(this).data('tingkatanid'));
  $(".modal-body #editIDSERTIFIKAT_DESKRIPSI").val($(this).data('desk'));
  $(".modal-body #editDELETION_STATUS").val($(this).data('status'));
  $.post("module/ajax/master/idsertifikat/aj_loadidcardfront.php", { EVENT_ID: id }, data => {
    $("#loadeditidfront").html(data);
  });
  $.post("module/ajax/master/idsertifikat/aj_loadidcardback.php", { EVENT_ID: id }, data => {
    $("#loadeditidback").html(data);
  });
  $.post("module/ajax/master/idsertifikat/aj_loadsertifikat.php", { EVENT_ID: id }, data => {
    $("#loadeditsertifikat").html(data);
  });
});