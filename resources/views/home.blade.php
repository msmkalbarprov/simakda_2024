@extends('template.app')
@section('title', 'Dashboard | SIMAKDA')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{'Dashboard'}}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{'App'}}</a></li>
                    <li class="breadcrumb-item active">{{'Dashboard'}}</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2">
                    <div id="total-revenue-chart" data-colors='["--bs-primary"]'></div>
                </div>
                <div>
                    <h6 class="mb-1 mt-1 ">Rp<span >{{nilai($data_pendapatan->pendapatan)}}</span></h6>
                    <p class="text-mute mb-0">Pendapatan</p>
                </div>
               
            </div>
        </div>
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body ">
                <div class="float-end mt-2">
                    <div id="growth-chart" data-colors='["--bs-warning"]'></div>
                    
                </div>
                <div>
                    <h6 class="mb-1 mt-1 ">Rp<span >{{ rupiah($data_belanja->belanja)}}</span></h6>
                    <p class="text-mute mb-0">Belanja</p>
                </div>
                
            </div>
        </div>
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body ">
                <div class="float-end mt-2">
                    <div id="total-revenue-chart2" data-colors='["--bs-primary"]'></div>
                </div>
                <div>
                    <h6 class="mb-1 mt-1 ">Rp<span >{{rupiah($data_pem_terima->pem_terima)}}</span></h6>
                    <p class="text-mute mb-0">Pembiayaan Penerimaan</p>
                </div>
                
            </div>
        </div>
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">

        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2 ">
                    <div id="total-revenue-chart4" data-colors='["--bs-warning"]'> </div>
                    
                </div>
                <div>
                    <h6 class="mb-1 mt-1 ">Rp<span >{{rupiah($data_pem_keluar->pem_keluar)}}</span></h6>
                    <p class="text-mute mb-0">Pembiayaan Pengeluaran</p>
                </div>
                
            </div>
        </div>
    </div> <!-- end col-->
</div> <!-- end row-->

{{-- ALERT START--}}
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    @php
        $pengumuman1 = pengumuman_top1();
    @endphp
    <i class="fa fa-info-circle"> {{$pengumuman1->judul}}</i>
    
    <p>{!!strip_tags($pengumuman1->isi,'<ul><ul/><li></li>')!!}</p>
    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
{{-- ALERT END --}}
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{'Pendapatan dan Belanja'}}</h4>
                
                <div id="pie_chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{'Pembiayaan'}}</h4>
                
                <div id="donut_chart" class="apex-charts"  dir="ltr"></div>
            </div>
        </div>
    </div>
</div>

<!-- apexcharts -->
<script src="{{asset('template/assets/libs/apexcharts/apexcharts.min.js')}}"></script>

<script src="{{asset('template/assets/js/pages/dashboard.init.js')}}"></script>
        <!-- apexcharts init -->
{{-- <script src="{{asset('template/assets/js/pages/apexcharts.init.js')}}"></script> --}}
<script>
    let pendapatan  = parseFloat("{{ $data_pendapatan->pendapatan }}");
    let belanja     = parseFloat("{{ $data_belanja->belanja }}");

   console.log(belanja);
    var options = {
  chart: {
      height: 320,
      type: 'donut',
  }, 
  series: [ pendapatan, belanja ],
  labels: ["Pendapatan", "Belanja"],
  colors: ["#34c38f", "#5b73e8"],
  legend: {
      show: true,
      position: 'bottom',
      horizontalAlign: 'center',
      verticalAlign: 'middle',
      floating: false,
      fontSize: '14px',
      offsetX: 0
  },
  responsive: [{
      breakpoint: 600,
      options: {
          chart: {
              height: 240
          },
          legend: {
              show: false
          },
      }
  }]

}

var chart = new ApexCharts(
  document.querySelector("#pie_chart"),
  options
);

chart.render();

// pembiayaan
var options = {
  chart: {
      height: 320,
      type: 'donut',
  }, 
  series: [{{ $data_pem_keluar->pem_keluar }}, {{$data_pem_terima->pem_terima}}],
  labels: ["Pengeluaran Pembiayaan", "Penerimaan Pembiayaan"],
  colors: ["#34c38f", "#5b73e8"],
  legend: {
      show: true,
      position: 'bottom',
      horizontalAlign: 'center',
      verticalAlign: 'middle',
      floating: false,
      fontSize: '14px',
      offsetX: 0
  },
  responsive: [{
      breakpoint: 600,
      options: {
          chart: {
              height: 240
          },
          legend: {
              show: false
          },
      }
  }]

}

var chart1 = new ApexCharts(
  document.querySelector("#donut_chart"),
  options
);

chart1.render();



  
</script>
@endsection
