<style>
  @media only screen and (max-width: 600px) {
    /* .fc-toolbar-chunk{
    display:none;
    } */
    .fc-dayGridMonth-button,.fc-timeGridWeek-button,.fc-timeGridDay-button,.fc-listWeek-button{
      display:none !important;
    }
    /* .fc-header-toolbar{
      display:none;
      margin-bottom:0px !important;
    } */
  }
  
</style>
<div class="page-body">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container-fluid calendar-basic">
    <div class="card">
      <div class="card-header">
        <h5 class="float-left">Kalender Meeting</h5>
        <button type="button" class="btn btn-secondary float-right" name="button" data-toggle="modal" data-target="#addModal">Tambah Agenda</button>
      </div>

      <div class="card-body">
        <div class="row" id="wrap">
          <!-- <div class="col-md-4 box-col-12">
            <div class="md-sidebar mb-3"><a class="btn btn-secondary md-sidebar-toggle" href="javascript:void(0)">calendar filter</a>
              <div class="md-sidebar-aside job-left-aside custom-scrollbar">
                <div id="external-events">
                  <h4>Draggable Events</h4>
                  <div id="external-events-list">
                    <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event">
                      <div class="fc-event-main"> <i class="fa fa-birthday-cake me-2"></i>Birthday Party</div>
                    </div>
                    <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event">
                      <div class="fc-event-main"> <i class="fa fa-user me-2"></i>Meeting With Team.</div>
                    </div>
                    <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event">
                      <div class="fc-event-main"> <i class="fa fa-plane me-2"></i>Tour &amp; Picnic</div>
                    </div>
                    <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event">
                      <div class="fc-event-main"> <i class="fa fa-file-text me-2"></i>Reporting Schedule</div>
                    </div>
                    <div class="fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event">
                      <div class="fc-event-main"> <i class="fa fa-briefcase me-2"></i>Lunch &amp; Break</div>
                    </div>
                  </div>
                  <p>
                    <input class="checkbox_animated" id="drop-remove" type="checkbox">
                    <label for="drop-remove">remove after drop</label>
                  </p>
                </div>
              </div>
            </div>
          </div> -->
          <div class="col-md-12 box-col-12">
            <div class="calendar-default" id="calendar-container">
              <div id="calendar"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Container-fluid Ends-->
</div>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="" action="<?=base_url()?>ClientMeeting/addActionCalendar" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Agenda Meeting</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="theme-form">
              <div class="form-group">
                <label class="col-form-label">Nama Klien</label>
                <select name="client_id" class="form-control">
                  <?php
                  foreach($clients as $client){

                  ?>
                  <option value="<?=$client->client_id?>"><?=$client->client_name?></option>

                  <?php } ?>
                </select>
                <!-- <input class="form-control" name="client_name" type="text" required> -->
              </div>
              <div class="form-group">
                <label class="col-form-label">Tanggal Meeting</label>
                <input class="datepicker-here form-control digits" type="text" data-language="en" name="meeting_date" required autocomplete="off">
                <!-- <input class="form-control" name="client_phone" type="text" required> -->
              </div>
              <div class="form-group">
                <label class="col-form-label">Jam Meeting</label>
                <input class="form-control" type="time" data-language="en" name="meeting_time" required autocomplete="off">
                <!-- <input class="form-control" name="client_phone" type="text" required> -->
              </div>
              <div class="form-group">
                <label class="col-form-label">Tempat Meeting</label>
                <input type="text" name="meeting_location" class="form-control" required>
              </div>
              <div class="form-group">
                <label class="col-form-label">Jenis Meeting</label>
                <select name="meeting_type" class="form-control">
                   <option value="Meeting Klien Baru">Meeting Klien Baru</option>
                   <option value="Checksite">Checksite</option>
                   <option value="Meeting Progress">Meeting Progress</option>
                </select>            
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
          <button type="submit" id="btn-add" class="btn btn-secondary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      },
      initialView: 'dayGridMonth',
      // initialDate: ,
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      selectable: true,
      nowIndicator: true,

      eventClick:  function(arg) {
                  // endtime = $.fullCalendar.moment(event.end).format('h:mm');
                  // starttime = $.fullCalendar.moment(event.start).format('dddd, MMMM Do YYYY, h:mm');
                  // var mywhen = starttime + ' - ' + endtime;
                  // alert(arg.event.title);
                  swal(arg.event.title);

                  // $('#title').html(arg.event.title);
                  // $('#modalWhen').text(arg.event.start);
                  // $('#eventID').val(arg.event.id);
                  // $('#calendarModal').modal();
              },
      events: [
        <?php foreach ($meetings as $meeting): ?>
          {
            title: '<?php echo html_escape($meeting->meeting_type).' Dengan '.$meeting->client_name.' di '.$meeting->meeting_location.' Pukul '.$meeting->meeting_time ?>',
            start:  '<?php echo html_escape($meeting->meeting_date)?>',
          },
        <?php endforeach; ?>
      ],
        
  });

  calendar.render();
});

</script>
