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
                     Laporan Keuangan</h3>
                </div>
                <div class="col-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href=""><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Laporan Keuangan</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              
              <div class="col-xl-12 col-md-12 xl-100 chart_data_left">
                <div class="card">
                  <div class="card-body p-0">
                    <div class="row m-0 chart-main">
                      <div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
                        <div class="media align-items-center">
                          <div class="media-body">
                            <div class="right-chart-content">
                              <h5>Rp.<?=number_format($total_income->total)?></h5><span>Total Income </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
                        <div class="media align-items-center">
                          <div class="media-body">
                            <div class="right-chart-content">
                              <h5>Rp.<?=number_format($total_profit->total)?></h5><span>Total Estimasi Profit</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
                        <div class="media align-items-center">
                          <div class="media-body">
                            <div class="right-chart-content">
                              <h5>Rp.<?=number_format($total_uang_masuk->total)?></h5><span>Total Uang Masuk</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-3 col-md-6 col-sm-6 p-0 box-col-6">
                        <div class="media border-none align-items-center">
                          <div class="media-body">
                         
                            <div class="right-chart-content">
                              <h5>Rp.<?=number_format($total_income->total - $total_uang_masuk->total)?></h5><span>Total Uang Diluar</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <div class="col-xl-3 xl-50 chart_data_right box-col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="media align-items-center">
                      <div class="media-body right-chart-content">
                        <h4>$95,900<span class="new-box">Hot</span></h4><span>Purchase Order Value</span>
                      </div>
                      <div class="knob-block text-center">
                        <input class="knob1" data-width="10" data-height="70" data-thickness=".3" data-angleoffset="0" data-linecap="round" data-fgcolor="#7366ff" data-bgcolor="#eef5fb" value="60">
                      </div>
                    </div>
                  </div>
                </div>
              </div> -->
            </div>
            <div class="row size-column">
              <div class="col-xl-12 box-col-12 xl-100">
                <div class="row dash-chart">
                <div class="col-xl-6 box-col-12 xl-50">
                    <div class="card o-hidden dash-chart">
                      <div class="card-header card-no-border">
                        <div class="media">
                          <div class="media-body">
                            <h5><span class="f-w-500 font-roboto">Income Bulan Ini</span></h5>
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
                  </div>
                  <div class="col-xl-6 box-col-12 xl-50">
                    <div class="card o-hidden dash-chart">
                      <div class="card-header card-no-border">
                        <div class="media">
                          <div class="media-body">
                            <h5><span class="f-w-500 font-roboto">Profit Bulan Ini</span></h5>
                            <?php $month = Date('M');?>
                            <h4 class="f-w-500 mb-0 f-26">Rp. <span class="counter"><?=number_format($total_profit_all[0]->$month)?></span></h4>
                          </div>
                        </div>
                      </div>
                      <div class="card-body p-0">
                        <div class="media">
                          <div class="media-body">
                            <div class="profit-card">
                              <div id="profit-chart"></div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="col-xl-6 box-col-8 col-md-8">
                    <div class="card" >
                      <div class="card-header">
                        <h5 class="pull-left mt-1">Financial Mecasa History</h5>
                        <button type="button" class="btn btn-primary float-right" name="button" data-toggle="modal" data-target="#addHistoryModal">Tambah History</button>
                        <br>

                      </div>
                      <div class="card-body p-4" style="border-radius:10px; overflow-y: scroll; height:500px;" id="financial-history-card">
                          <p class="text-success" style="font-weight: bold; margin-bottom:0px;"> TOTAL MASUK <span class="pull-right">Rp. <?=number_format($mecasa_masuk_total->total)?> </span> </p>
                          <p  class="text-secondary" style="font-weight: bold; margin-bottom:0px;"> TOTAL KELUAR <span class="pull-right">Rp. <?=number_format($mecasa_keluar_total->total)?></span></p>
                          <p style="font-weight: bold; margin-bottom:0px;"> TOTAL MARGIN <span class="pull-right">Rp. <?=number_format($mecasa_masuk_total->total - $mecasa_keluar_total->total)?></span></p>
                          <hr>       
                        <ul class="list-group list-group-flush" id="financial-history-div">

                        </ul> 
                        <p class="text-center text-info mt-3" id="load_data_message"></p>

                      </div>
                    </div>
                  </div>
                  

                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
        <div class="modal fade" id="addHistoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <form class="" action="<?=base_url()?>Laporan/addHistoryAction" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Tambah History Keuangan</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="theme-form">
                      <div class="form-group">
                        <label>Tanggal</label>
                        <input class="datepicker-here form-control digits" type="text" data-language="en" name="financial_date" required autocomplete="off">
                        <!-- <input class="form-control" type="text" name="project_name" placeholder="Nama Project*" required> -->
                      </div>
                  
                      <div class="form-group">
                        <label class="col-form-label">Tipe History Keuangan</label>
                        <select class="form-control" name="financial_type" required>
                          <option value="">-</option>
                          <option value="Uang Keluar">Uang Keluar</option>
                          <option value="Uang Masuk">Uang Masuk</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Keterangan</label>
                        <input class="form-control" type="text" name="financial_note" required>
                      </div>
                      <div class="form-group">
                        <label>Nominal</label>
                        <input class="form-control digits autonumeric" type="text" name="financial_nominal" required autocomplete="off">
                      </div>
                      <div class="form-group">
                        <label>Financial PIC</label>
                        <input class="form-control" type="text" name="financial_pic" required>
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
        <div class="modal fade" id="editHistoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <form class="" action="<?=base_url()?>Laporan/updateHistoryAction" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit History Keuangan</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="theme-form">
                      <div class="form-group">
                        <input type="hidden" name="mecasa_financial_id" id="mecasa_financial_id" >
                        <label>Tanggal</label>
                        <input class="datepicker-here form-control digits" type="text" data-language="en" name="financial_date" id="financial_date" required autocomplete="off">
                        <!-- <input class="form-control" type="text" name="project_name" placeholder="Nama Project*" required> -->
                      </div>
                  
                      <div class="form-group">
                        <label class="col-form-label">Tipe History Keuangan</label>
                        <select class="form-control" name="financial_type" id="financial_type" required>
                          <option value="">-</option>
                          <option value="Uang Keluar">Uang Keluar</option>
                          <option value="Uang Masuk">Uang Masuk</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label>Keterangan</label>
                        <input class="form-control" type="text" name="financial_note" id="financial_note" required>
                      </div>
                      <div class="form-group">
                        <label>Nominal</label>
                        <input class="form-control digits autonumeric2" type="text" name="financial_nominal" id="financial_nominal" required autocomplete="off">
                      </div>
                      <div class="form-group">
                        <label>Financial PIC</label>
                        <input class="form-control" type="text" name="financial_pic" id="financial_pic" required>
                      </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <a href="" id="removeHistoryFinancialButton"><button type="button" class="btn btn-danger" >Hapus</button></a>
                  <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                  <button type="submit" id="btn-add" class="btn btn-secondary">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>


        <script>
          $(document).ready(function(){

            var limit = 5;
            var start = 0;
            var action = 'inactive';
            function lazzy_loader(limit)
            {
              var output = '';
              for(var count=0; count<limit; count++)
              {
                output += '<div class="post_data">';
                output += '<p><span class="content-placeholder" style="width:100%; height: 30px;">&nbsp;</span></p>';
                output += '<p><span class="content-placeholder" style="width:100%; height: 100px;">&nbsp;</span></p>';
                output += '</div>';
              }
              // $('#top-home').html(output);
            }

            lazzy_loader(limit);

            function load_data(limit, start)
            {
              $.ajax({
                url:"<?php echo base_url(); ?>Laporan/fetchHistoryFinancial",
                method:"POST",
                data:{limit:limit, start:start},
                cache: false,
                success:function(data)
                {
                  if(data == '')
                  {
                    $('#load_data_message').html('<h7><i>No More Result Found</i></h7>');
                    action = 'active';
                  }
                  else
                  {
                    $('#financial-history-div').append(data);
                    $('#load_data_message').html("");
                    action = 'inactive';
                  }
                }
              })
            }

            if(action == 'inactive')
            {
              action = 'active';
              load_data(limit, start);
            }

            const element = document.querySelector("div#financial-history-card");
            element.addEventListener("scroll", (event) => {
              if(action == 'inactive')
              {
                action = 'active';
                start = start + limit;
                $('#load_data_message').html('<h7><i>Please Wait...</i></h7>');
                setTimeout(function(){
                  load_data(limit, start);
                }, 500);
              }

              
            });
            // $(window).scroll(function(){
            //   // alert('loaded');
            //   if($(window).scrollTop() + $(window).height() > $("#financial-history-card").height() && action == 'inactive')
            //   {
            //     action = 'active';
            //     start = start + limit;
            //     $('#load_data_message').html('<h7><i>Please Wait...</i></h7>');
            //     setTimeout(function(){
            //       load_data(limit, start);
            //     }, 500);
            //   }
            // });

          });
          
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

            }, {
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

          var chart2 = new ApexCharts(document.querySelector("#profit-chart"),
          options2
          );

          chart2.render();

          function updateHistoryFinancial(mecasa_financial_id) {
            $.ajax({
              type: "POST",
              url: "<?php echo site_url('Laporan/getHistoryFinancialById');?>",
              data: "mecasa_financial_id="+mecasa_financial_id,
              success: function (response) {
                var row = JSON.parse(response);
                console.log(row);
                $('#mecasa_financial_id').val(row.mecasa_financial_id);
                $('#financial_date').val(row.financial_date);
                $('#financial_note').val(row.financial_note);
                $('#financial_nominal').val(row.financial_nominal);
                $('#financial_pic').val(row.financial_pic);
                $('#removeHistoryFinancialButton').prop("href", "<?=base_url()?>Laporan/deleteHistoryFinancialAction/"+row.mecasa_financial_id);
                $("select#financial_type option[value='"+row.financial_type+"']").prop("selected","selected");

              }
            });
          }
</script>