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
                      <div class="col-xl-4 col-md-6 col-sm-6 p-0 box-col-6">
                        <div class="media align-items-center">
                          <div class="media-body">
                            <div class="right-chart-content">
                              <h5>Rp.<?=number_format($total_uang_masuk_produksi)?></h5><span>Saldo Produksi </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-md-6 col-sm-6 p-0 box-col-6">
                        <div class="media align-items-center">
                          <div class="media-body">
                            <div class="right-chart-content">
                              <h5>Rp.<?=number_format($total_uang_masuk_design)?></h5><span>Saldo Design <i class="mdi mdi-sort-bool-descending-variant:"></i></span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-md-6 col-sm-6 p-0 box-col-6">
                        <div class="media align-items-center">
                          <div class="media-body">
                            <div class="right-chart-content">
                              <h5>Rp.<?=number_format($mecasa_masuk_total->total - $mecasa_keluar_total->total)?></h5><span>Saldo Kas</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row m-0 mt-2 chart-main" style="border-top:1px solid #ecf3fa">
                      <div class="col-xl-4 col-md-6 col-sm-6 p-0 box-col-6">
                        <div class="media align-items-center">
                          <div class="media-body">
                            <div class="right-chart-content">
                              <h5>Rp.<?=number_format($total_uang_sisa_produksi)?></h5><span>Sisa Uang Produksi</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-md-6 col-sm-6 p-0 box-col-6">
                        <div class="media align-items-center">
                          <div class="media-body">
                            <div class="right-chart-content">
                              <h5>Rp.<?=number_format($total_uang_sisa_design)?></h5><span>Sisa Uang Design</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-4 col-md-6 col-sm-6 p-0 box-col-6">
                          <div class="media align-items-center">
                            <div class="media-body">
                              <div class="right-chart-content">
                                <h5>Rp.<?=number_format(($mecasa_masuk_total->total - $mecasa_keluar_total->total) + $total_uang_masuk_design + $total_uang_masuk_produksi)?></h5><span>Saldo Keseluruhan</span>
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
                  <div class="col-xl-12 box-col-12 box-col-12 col-md-12">
                    <div class="card o-hidden dash-chart">
                      <div class="card-header card-no-border">
                        <div class="media">
                          <div class="media-body">
                            <h5><span class="f-w-500 font-roboto">Laporan Kas Mecasa</span></h5>
                          </div>
                        </div>
                      </div>
                      <div class="card-body p-0">
                        <div class="media">
                          <div class="media-body">
                            <div class="monthly-visit" style="margin-bottom:0px !important;">
                              <div id="income-chart"></div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="col-xl-12 box-col-12 col-md-12">
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
                        <select class="form-control financial_type" name="financial_type" id="financial_type_add" required>
                          <option value="">-</option>
                          <option value="Uang Keluar">Uang Keluar</option>
                          <option value="Uang Masuk">Uang Masuk</option>
                        </select>
                      </div>
                      <div class="form-group" id="financial_out_type_div" style="display:none;">
                        <label class="col-form-label">Tipe Uang Keluar</label>
                        <select class="form-control" name="financial_out_type">
                          <option value="" selected>-</option>
                          <option value="Operasional">Operasional</option>
                          <option value="Asset">Asset</option>
                          <option value="Pembangunan">Pembangunan</option>
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
                        <select class="form-control financial_type" name="financial_type" id="financial_type" required>
                          <option value="">-</option>
                          <option value="Uang Keluar">Uang Keluar</option>
                          <option value="Uang Masuk">Uang Masuk</option>
                        </select>
                      </div>
                      <div class="form-group" id="financial_out_type_div_edit"style="display:none;">
                        <label class="col-form-label">Tipe Uang Keluar</label>
                        <select class="form-control" name="financial_out_type" id="financial_out_type">
                          <option value="" selected>-</option>
                          <option value="Operasional">Operasional</option>
                          <option value="Asset">Asset</option>
                          <option value="Pembangunan">Pembangunan</option>
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
                
            $("#financial_type_add").change(function(){
              var financial_type =  $(this).val();
              if(financial_type == 'Uang Keluar'){
                $('#financial_out_type_div').show();
              }
              else{
                $('#financial_out_type_div').hide();
              }
            });
            $("#financial_type").change(function(){
              var financial_type =  $(this).val();
              if(financial_type == 'Uang Keluar'){
                $('#financial_out_type_div_edit').show();
              }
              else{
                $('#financial_out_type_div_edit').hide();
              }
            });
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
          
          
// Project Masuk
// var test1 = ;
// var test = new Intl.NumberFormat('en-ID').format(test1);
// alert(test);
var options3 = {
  series: [{
    name: 'Uang Masuk',
    data: [<?=$mecasa_masuk_monthly[0]->Jan?>,
    <?=$mecasa_masuk_monthly[0]->Feb?>,
    <?=$mecasa_masuk_monthly[0]->Mar?>,
    <?=$mecasa_masuk_monthly[0]->Apr?>,
    <?=$mecasa_masuk_monthly[0]->May?>,
    <?=$mecasa_masuk_monthly[0]->Jun?>,
    <?=$mecasa_masuk_monthly[0]->Jul?>,
    <?=$mecasa_masuk_monthly[0]->Aug?>,
    <?=$mecasa_masuk_monthly[0]->Sep?>,
    <?=$mecasa_masuk_monthly[0]->Oct?>,
    <?=$mecasa_masuk_monthly[0]->Nov?>,
    <?=$mecasa_masuk_monthly[0]->Dec?>]

  },{
    name: 'Uang Keluar',
    data: [<?=$mecasa_keluar_monthly[0]->Jan?>,
    <?=$mecasa_keluar_monthly[0]->Feb?>,
    <?=$mecasa_keluar_monthly[0]->Mar?>,
    <?=$mecasa_keluar_monthly[0]->Apr?>,
    <?=$mecasa_keluar_monthly[0]->May?>,
    <?=$mecasa_keluar_monthly[0]->Jun?>,
    <?=$mecasa_keluar_monthly[0]->Jul?>,
    <?=$mecasa_keluar_monthly[0]->Aug?>,
    <?=$mecasa_keluar_monthly[0]->Sep?>,
    <?=$mecasa_keluar_monthly[0]->Oct?>,
    <?=$mecasa_keluar_monthly[0]->Nov?>,
    <?=$mecasa_keluar_monthly[0]->Dec?>]
  }],
  chart: {
    height: 300,
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

      columnWidth: '35%',
      startingShape: 'rounded',
      endingShape: 'rounded'
    }
  },
  dataLabels: {
    enabled: false,

    formatter: function (val) {
      return "Rp." + val;
    },
    offsetY: 0,
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
      show: true
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
        return "Rp." + new Intl.NumberFormat('en-ID').format(val);
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
  colors: [ CubaAdminConfig.primary, '#f68027'   ],

};

var chart3 = new ApexCharts(document.querySelector("#income-chart"),
  options3
);

chart3.render();


          function updateHistoryFinancial(mecasa_financial_id) {

            var element = AutoNumeric.getAutoNumericElement('.autonumeric2');
            // element.remove();

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
                element.remove();    
                new AutoNumeric('.autonumeric2', {
                    currencySymbol : '',
                    digitGroupSeparator : ',',
                    decimalPlaces	: '0'
                   });

           

                $('#removeHistoryFinancialButton').prop("href", "<?=base_url()?>Laporan/deleteHistoryFinancialAction/"+row.mecasa_financial_id);
                $("select#financial_type option[value='"+row.financial_type+"']").prop("selected","selected");
                if(row.financial_type=='Uang Masuk'){
                  $('#financial_out_type_div_edit').hide()
                }else{
                  $('#financial_out_type_div_edit').show()
                }
                $("select#financial_out_type option[value='"+row.financial_out_type+"']").prop("selected","selected");

              }
            });
          }
</script>