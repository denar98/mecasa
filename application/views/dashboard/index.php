<style>
  .fc-toolbar-chunk{
    display:none;
  }
  .fc-header-toolbar{
    display:none;
    margin-bottom:0px !important;
  }
</style>
        <!-- Page Sidebar Ends-->
        <?php $month = Date('M');?>

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6">
                  <h3>
                     Dasboard</h3>
                </div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=""><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Dashboard</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row size-column">
              <div class="col-xl-7 box-col-12 xl-100">
                <div class="row dash-chart">
                  <div class="col-xl-6 box-col-6 col-md-6">
                    <div class="card o-hidden">
                      <div class="card-header card-no-border">
                        <div class="media">
                          <div class="media-body">
                            <p><span class="f-w-500 font-roboto">Project Masuk</span></p>
                            <h4 class="f-w-500 mb-0 f-26 "><span class="counter"><?=number_format($project_masuk_all[0]->$month)?></span> Project</h4>
                          </div>
                        </div>
                      </div>
                      <div class="card-body pt-0">
                        <div class="monthly-visit" style="margin-bottom:0px;">
                          <div id="project-masuk-chart"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6 xl-50 box-col-12">
                    <div class="card">
                      <div class="card-header card-no-border">
                        <h5>On Progress Project</h5>
                      </div>
                      <div class="card-body pt-0">
                        <div class="our-product">
                          <div class="table-responsive">
                            <table class="table table-bordernone">
                              <tbody class="f-w-500">
                                <?php
                                  foreach($on_progress_project as $on_progress_project_row){
                                ?>
                                <tr>
                                  <td>
                                    <div class="media">
                                      <?php
                                        if($on_progress_project_row->project_detail_type == "Design"){
                                      ?>
                                        <div class="light-bg-primary" style="width:40px; height:40px; margin:3px 8px 0 0;  border-radius:100%; background:rgba(115, 102, 255, 0.15);"><i class="fa fa-pencil text-primary" style="width:20px; font-size:20px; height:20px; margin:10px  12px !important;" aria-hidden="true"></i></div>
                                      <?php }else{ ?>
                                        <div class="light-bg-primary" style="width:40px; height:40px; margin:3px 8px 0 0;  border-radius:100%; background:#ffa08b63;"><i class="fa fa-home text-secondary" style="width:20px; font-size:20px; height:20px; margin:10px  12px !important;" aria-hidden="true"></i></div>
                                      <?php } ?>
                                      <div class="media-body"><span><?=$on_progress_project_row->project_name?></span>
                                        <!-- <p class="font-roboto"><?=$on_progress_project_row->project_detail_status?></p> -->
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <p>Jenis</p><span><?=$on_progress_project_row->project_detail_type?></span>
                                  </td>
                                  <td>
                                    <p>Nominal</p><span><?=number_format($on_progress_project_row->project_detail_nominal)?></span>
                                  </td>
                                </tr>
                                <?php } ?>
                                
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="col-xl-6 box-col-6 col-md-6">
                    <div class="card o-hidden">
                      <div class="card-header card-no-border">
                        <div class="media">
                          <div class="media-body">
                            <p><span class="f-w-500 font-roboto">Total Estimasi Profit</span></p>
                            <h4 class="f-w-500 mb-0 f-26">Rp.<span class="counter"> <?=number_format($total_profit_all[0]->$month)?></span></h4>
                          </div>
                        </div>
                      </div>
                      <div class="card-body pt-0">
                        <div class="monthly-visit">
                        <div id="profit-chart"></div>
                        </div>
                      </div>
                    </div>
                  </div> -->

                  <!-- <div class="col-xl-6 box-col-6 col-md-6">
                    <div class="card o-hidden">
                      <div class="card-header card-no-border">
                        <div class="media">
                          <div class="media-body">
                            <p><span class="f-w-500 font-roboto">Total Estimasi Profit</span></p>
                            <h4 class="f-w-500 mb-0 f-26">Rp.<span class="counter"> <?=number_format($total_profit_all[0]->$month)?></span></h4>
                          </div>
                        </div>
                      </div>
                      <div class="card-body p-0">
                        <div class="monthly-visit">
                          <div id="profit-chart"></div>
                        </div>
                      </div>
                    </div>
                  </div> -->
                  
                  <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                    <div class="card o-hidden">
                      <div class="card-body">
                        <div class="ecommerce-widgets media">
                          <div class="media-body">
                            <p class="f-w-500 font-roboto">Project Design<span class="badge pill-badge-primary ml-3">On Progress</span></p>
                            <h4 class="f-w-500 mb-0 f-26"><span class="counter"><?=$on_progress_project_design->num?></span> Project</h4>
                          </div>
                          <div class="ecommerce-box light-bg-primary"><i class="fa fa-pencil text-primary" style="width:25px; font-size:28px; height:25px; margin-left:12px !important;" aria-hidden="true"></i></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6 box-col-6 col-lg-12 col-md-6">
                    <div class="card o-hidden">
                      <div class="card-body">
                        <div class="media">
                          <div class="media-body">
                            <div class="ecommerce-widgets media">
                              <div class="media-body">
                                <p class="f-w-500 font-roboto">Project Produksi<span class="badge pill-badge-secondary ml-3">On Progress</span></p>
                                <h4 class="f-w-500 mb-0 f-26"><span class="counter"><?=$on_progress_project_produksi->num?></span> </span> Project</h4>
                              </div>
                              <div class="ecommerce-box light-bg-secondary"><i class="fa fa-home text-secondary" style="width:30px; font-size:32px; height:30px;margin:0px !important;" aria-hidden="true"></i></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <div class="col-xl-5 box-col-12 xl-50">
                <div class="card o-hidden dash-chart">
                  <div class="card-header card-no-border">
                    <div class="media">
                      <div class="media-body">
                        <p><span class="f-w-500 font-roboto">Total Income</span></p>
                        <?php $month = Date('M');?>
                        <h4 class="f-w-500 mb-0 f-26">Rp. <span class="counter"><?=number_format($total_income_all[0]->$month)?></span></h4>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="media">
                      <div class="media-body">
                        <div class="profit-card">
                          <div id="income-chart"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div> -->
              
              <div class="col-xl-4 xl-50 box-col-12">
                <div class="card">
                  <div class="card-header card-no-border">
                    <h5>Kalender Meeting <?=Date("M-Y")?></h5>
                    
                  </div>
                  <div class="card-body pt-0">
                    <div class="col-md-12 box-col-12">
                      <div class="calendar-default" id="calendar-container">
                        <div id="calendar"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-4 xl-50 box-col-12">
                <div class="card">
                  <div class="card-header card-no-border">
                    <h5>List Meeting Hari Ini</h5>
                  </div>
                  <div class="card-body new-update pt-0">
                    <div class="activity-timeline">
                      <?php
                        foreach($today_meetings as $today_meetings_row){
                      ?>
                      <div class="media" style="margin-top:25px;">
                        <div class="activity-line" style="top:100x; height:72.5%;"></div>
                        <div class="activity-dot-secondary" style="top:-10px;"></div>
                        <div class="media-body"><span><?=$today_meetings_row->meeting_time?></span>
                          <p class="font-roboto"><?php echo html_escape($today_meetings_row->meeting_type).' Dengan '.$today_meetings_row->client_name.' di '.$today_meetings_row->meeting_location ?></p>
                        </div>
                      </div>
                      <?php } ?>
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
  <script>
    // Monthly sale
var options1 = {

  series: [{
    name: 'Design',
    data: [<?=$total_income_design[0]->Jan?>,
    <?=$total_income_design[0]->Feb?>,
    <?=$total_income_design[0]->Mar?>,
    <?=$total_income_design[0]->Apr?>,
    <?=$total_income_design[0]->May?>,
    <?=$total_income_design[0]->Jun?>,
    <?=$total_income_design[0]->Jul?>,
    <?=$total_income_design[0]->Aug?>,
    <?=$total_income_design[0]->Sep?>,
    <?=$total_income_design[0]->Oct?>,
    <?=$total_income_design[0]->Nov?>,
    <?=$total_income_design[0]->Dec?>]

  }, {
    name: 'Produksi',
    data: [<?=$total_income_produksi[0]->Jan?>,
    <?=$total_income_produksi[0]->Feb?>,
    <?=$total_income_produksi[0]->Mar?>,
    <?=$total_income_produksi[0]->Apr?>,
    <?=$total_income_produksi[0]->May?>,
    <?=$total_income_produksi[0]->Jun?>,
    <?=$total_income_produksi[0]->Jul?>,
    <?=$total_income_produksi[0]->Aug?>,
    <?=$total_income_produksi[0]->Sep?>,
    <?=$total_income_produksi[0]->Oct?>,
    <?=$total_income_produksi[0]->Nov?>,
    <?=$total_income_produksi[0]->Dec?>]

  }, ],
  chart: {
    height: 329,
    // width: 80,
    type: 'area',
    toolbar: {
      show: false
    },
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    curve: 'smooth',
    width: 0
  },
  
  xaxis: {
    // type: 'text',
    categories: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
    labels: {
      show: true
    },
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    },
    tooltip: {
      enabled: false,
    },
  },
  tooltip: {
    x: {
      format: 'dd/MM/yy HH:mm'
    },
  },
  legend: {
    show: false,
  },
  grid: {
    xaxis: {
      lines: {
        show: false
      }
    },
    yaxis: {
      lines: {
        show: true
      }
    },
  },
  fill: {
    type: 'gradient',
    opacity: [0.9, 1],
    gradient: {
      shade: 'light',
      type: 'horizontal',
      shadeIntensity: 1,
      // gradientToColors: ['#307EA5', '#a927f9', '#073D5E'],
      opacityFrom: [0.9, 1],
      opacityTo: [0.9, 1],
      stops: [30, 100],
      colorStops: []
    },
    colors: [ CubaAdminConfig.primary , CubaAdminConfig.secondary],
  },
  colors: [CubaAdminConfig.primary, CubaAdminConfig.secondary],
};

var chart1 = new ApexCharts(document.querySelector("#income-chart"),
  options1
);

chart1.render();

// Project Masuk
var options3 = {
  series: [{
    name: 'Design',
    data: [<?=$project_masuk_design[0]->Jan?>,
    <?=$project_masuk_design[0]->Feb?>,
    <?=$project_masuk_design[0]->Mar?>,
    <?=$project_masuk_design[0]->Apr?>,
    <?=$project_masuk_design[0]->May?>,
    <?=$project_masuk_design[0]->Jun?>,
    <?=$project_masuk_design[0]->Jul?>,
    <?=$project_masuk_design[0]->Aug?>,
    <?=$project_masuk_design[0]->Sep?>,
    <?=$project_masuk_design[0]->Oct?>,
    <?=$project_masuk_design[0]->Nov?>,
    <?=$project_masuk_design[0]->Dec?>]
  },{
    name: 'Produksi',
    data: [<?=$project_masuk_produksi[0]->Jan?>,
    <?=$project_masuk_produksi[0]->Feb?>,
    <?=$project_masuk_produksi[0]->Mar?>,
    <?=$project_masuk_produksi[0]->Apr?>,
    <?=$project_masuk_produksi[0]->May?>,
    <?=$project_masuk_produksi[0]->Jun?>,
    <?=$project_masuk_produksi[0]->Jul?>,
    <?=$project_masuk_produksi[0]->Aug?>,
    <?=$project_masuk_produksi[0]->Sep?>,
    <?=$project_masuk_produksi[0]->Oct?>,
    <?=$project_masuk_produksi[0]->Nov?>,
    <?=$project_masuk_produksi[0]->Dec?>]
  }],
  chart: {
    height: 242,
    type: 'bar',
    stacked: false,
    toolbar: {
      show: false
    },
  },
  plotOptions: {
    bar: {
      dataLabels: {
        position: 'top', // top, center, bottom
      },

      columnWidth: '70%',
      startingShape: 'rounded',
      endingShape: 'rounded'
    }
  },
  dataLabels: {
    enabled: false,

    formatter: function (val) {
      return val + " Project";
    },
    offsetY: -10,
    style: {
      fontSize: '12px',
      colors: [ CubaAdminConfig.primary ]
    }
  },

  xaxis: {
    categories: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "October", "November", "Desember"],
    position: 'bottom',

    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    },
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#073D5E',
          colorTo: '#c481ec',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5,
        }
      }
    },
    // tooltip: {
    //   enabled: true,
    // },
    labels: {
      show: false
    }

  },
  yaxis: {
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false,
    },
    labels: {
      show: true,
      formatter: function (val) {
        return val + " Project";
      }
    }

  },
  grid: {
    show: true,
    padding: {
      top: 0,
      right: 0,
      bottom: 0,
      left: 0
    },
  },
  colors: [ CubaAdminConfig.primary,CubaAdminConfig.secondary ],

};

var chart3 = new ApexCharts(document.querySelector("#project-masuk-chart"),
  options3
);

chart3.render();



// total earning
var options2 = {
  series: [{
    name: 'Design',
    data: [<?=$total_profit_design[0]->Jan?>,
    <?=$total_profit_design[0]->Feb?>,
    <?=$total_profit_design[0]->Mar?>,
    <?=$total_profit_design[0]->Apr?>,
    <?=$total_profit_design[0]->May?>,
    <?=$total_profit_design[0]->Jun?>,
    <?=$total_profit_design[0]->Jul?>,
    <?=$total_profit_design[0]->Aug?>,
    <?=$total_profit_design[0]->Sep?>,
    <?=$total_profit_design[0]->Oct?>,
    <?=$total_profit_design[0]->Nov?>,
    <?=$total_profit_design[0]->Dec?>]
  },{
    name: 'Produksi',
    data: [<?=$total_profit_produksi[0]->Jan?>,
    <?=$total_profit_produksi[0]->Feb?>,
    <?=$total_profit_produksi[0]->Mar?>,
    <?=$total_profit_produksi[0]->Apr?>,
    <?=$total_profit_produksi[0]->May?>,
    <?=$total_profit_produksi[0]->Jun?>,
    <?=$total_profit_produksi[0]->Jul?>,
    <?=$total_profit_produksi[0]->Aug?>,
    <?=$total_profit_produksi[0]->Sep?>,
    <?=$total_profit_produksi[0]->Oct?>,
    <?=$total_profit_produksi[0]->Nov?>,
    <?=$total_profit_produksi[0]->Dec?>]
  }],
  chart: {
    height: 105,
    type: 'bar',
    stacked: true,
    toolbar: {
      show: false
    },
  },
  plotOptions: {
    bar: {
      dataLabels: {
        position: 'top', // top, center, bottom
      },

      columnWidth: '20%',
      startingShape: 'rounded',
      endingShape: 'rounded'
    }
  },
  dataLabels: {
    enabled: false,

    formatter: function (val) {
      return "Rp." + val;
    },
    offsetY: -10,
    style: {
      fontSize: '12px',
      colors: [ CubaAdminConfig.primary ]
    }
  },

  xaxis: {
    categories: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "October", "November", "Desember"],
    position: 'bottom',

    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false
    },
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#073D5E',
          colorTo: '#c481ec',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5,
        }
      }
    },
    // tooltip: {
    //   enabled: true,
    // },
    labels: {
      show: false
    }

  },
  yaxis: {
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false,
    },
    labels: {
      show: false,
      formatter: function (val) {
        return "Rp." + val;
      }
    }

  },
  grid: {
    show: false,
    padding: {
      top: -35,
      right: -45,
      bottom: -20,
      left: -10
    },
  },
  colors: [ CubaAdminConfig.primary,CubaAdminConfig.secondary ],
};

var chart2 = new ApexCharts(document.querySelector("#profit-chart"),
  options2
);

chart2.render();

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
