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
    
    <p>{!! html_entity_decode($pengumuman1->isi, ENT_QUOTES, 'UTF-8') !!}</p>
    <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
{{-- ALERT END --}}
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{'Pagu Anggaran'}}</h4>
                <div id="chartdiv" ></div>
                {{-- <div id="pie_chart" class="apex-charts" dir="ltr"></div> --}}
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{'Pengeluaran'}}</h4>
                <div id="chartdiv2"></div>
                {{-- <div id="donut_chart" class="apex-charts"  dir="ltr"></div> --}}
            </div>
        </div>
    </div>
</div>

<!-- apexcharts -->
<script src="{{asset('template/assets/libs/apexcharts/apexcharts.min.js')}}"></script>

<script src="{{asset('template/assets/js/pages/dashboard.init.js')}}"></script>
        <!-- apexcharts init -->

{{-- AMCHART --}}
<style>
    #chartdiv {
      width: 100%;
      height: 300px;
    }
    body {
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
}
    </style>
    
    <!-- Resources -->
    <script src="{{asset('js/amchart/core.js')}}"></script>
    <script src="{{asset('js/amchart/charts.js')}}"></script>
    <script src="{{asset('js/amchart/animated.js')}}"></script>
    <script src="{{asset('js/amchart/dataviz.js')}}"></script>
    <script src="{{asset('js/amchart/kelly.js')}}"></script>
    
    
    <!-- Chart code -->
    <script>
  
    // Data
    let pendapatan  = parseFloat("{{ $data_pendapatan->pendapatan }}");
    let belanja     = parseFloat("{{ $data_belanja->belanja }}");
    let pem_terima  = parseFloat("{{ $data_pem_terima->pem_terima }}");
    let pem_keluar  = parseFloat("{{ $data_pem_keluar->pem_keluar }}");
    

    // get day
    const d = new Date();
    let day = d.getDay()
    if(day == 0){//merah ok
      warna4="#fdd7d2";
      warna3="#f48e8a";
      warna2="#b91f26";
      warna1="#961b1e";
      warna5="#f48e8a";
    }else if(day == 1){//orange
      warna1="#d80b8c";
      warna2="#da6fab";
      warna3="#e796c1";
      warna4="#fde9f1";
      warna5="#da6fab";
    }else if(day == 2){//biru ok
      warna4="#b0cbdc";
      warna3="#2a9df4";
      warna2="#1167b1";
      warna1="#03254c";
      warna5="#1167b1";
    }else if(day == 3){//ungu
      warna4="#e0cde0";
      warna3="#c6aadb";
      warna2="#c587d0";
      warna1="#872a92";
      warna5="#c587d0";
    }else if(day == 4){//green
      warna1="#18723f";
      warna2="#3b9a56";
      warna3="#6fbf76";
      warna4="#eff8b9";
      warna5="#3b9a56";
    }else if(day == 5){//pink
      warna1="#a73300";
      warna2="#ff9565";
      warna3="#ffd0bc";
      warna4="#ffefe8";
      warna5="#ff9565";
    }else{//yellow
      warna1="#e5b045";
      warna2="#ffc44d";
      warna3="#ffd071";
      warna4="#f6e9cd";
      warna5="#ffc44d";
    }
    
  
    am4core.useTheme(am4themes_animated);
am4core.useTheme(am4themes_dataviz);

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.PieChart);

// Add data
chart.data = [{
  "Akun": "Pendapatan",
  "nilai": pendapatan,
  "color": am4core.color(warna1)
}, {
  "Akun": "Belanja",
  "nilai": belanja,
  "color": am4core.color(warna2)
}, {
  "Akun": "Penerimaan Pembiayaan",
  "nilai": pem_terima,
  "color": am4core.color(warna3)
}, {
  "Akun": "Pengeluaran Pembiayaan",
  "nilai": pem_keluar,
  "color": am4core.color(warna4)
}];

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "nilai";
pieSeries.dataFields.category = "Akun";
pieSeries.slices.template.propertyFields.fill = "color";

chart.innerRadius = am4core.percent(40);
chart.hiddenState.properties.innerRadius = am4core.percent(0);
chart.hiddenState.properties.radius = am4core.percent(100);

// Let's cut a hole in our Pie chart the size of 40% the radius
// chart.innerRadius = am4core.percent(40);
pieSeries.hiddenState.properties.endAngle = -90;

// Set up fills
pieSeries.slices.template.fillOpacity = 1;

var hs = pieSeries.slices.template.states.getKey("hover");
hs.properties.scale = 1;
hs.properties.fillOpacity = 0.5;

var label = pieSeries.createChild(am4core.Label);
label.text = "{{tahun_anggaran()}}";
label.horizontalCenter = "middle";
label.verticalCenter = "middle";
label.fontSize = 30;

// {{-- AMCHART --}}
</script>
<style>
    #chartdiv2 {
      width: 100%;
      height: 300px;
    }
    </style>
    
    
    <!-- Chart code -->
    <script>
        
            let penagihan   = parseFloat("{{ $data_penagihan->penagihan }}");
            let totaltagih   = parseFloat("{{ $data_penagihan->totaltagih }}");
            let spp         = parseFloat("{{ $data_spp->spp }}");
            let totalspp         = parseFloat("{{ $data_spp->total }}");
            let spm         = parseFloat("{{ $data_spm->spm }}");
            let totalspm         = parseFloat("{{ $data_spm->total }}");
            let sp2d        = parseFloat("{{ $data_sp2d->sp2d }}");
            let totalsp2d        = parseFloat("{{ $data_sp2d->total }}");
        // Apply chart themes
am4core.useTheme(am4themes_animated);
am4core.useTheme(am4themes_kelly);

// Create chart instance
var chart = am4core.create("chartdiv2", am4charts.XYChart);

// Add data
chart.data = [{
  "jenis": "penagihan",
  "jumlah": totaltagih,
  "nilai": penagihan.toFixed(2)
}, {
  "jenis": "SPP",
  "jumlah": totalspp,
  "nilai": spp.toFixed(2)
}, {
  "jenis": "SPM",
  "jumlah": totalspm,
  "nilai": spm.toFixed(2)
}, {
  "jenis": "SP2D",
  "jumlah": totalsp2d,
  "nilai": sp2d.toFixed(2)
}];

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "jenis";
categoryAxis.title.text = "Berkas";

// First value axis
var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "Realisasi";

// Second value axis
var valueAxis2 = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis2.title.text = "jumlah berkas";
valueAxis2.renderer.opposite = true;


// First series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "nilai";
series.dataFields.categoryX = "jenis";
series.name = "nilai";
series.tooltipText = "{name}: [bold]Rp{valueY}[/]";

// Second series
var series2 = chart.series.push(new am4charts.LineSeries());
series2.dataFields.valueY = "jumlah";
series2.dataFields.categoryX = "jenis";
series2.name = "jumlah";
series2.tooltipText = "{name}: [bold]{valueY}[/] Berkas";
series2.strokeWidth = 3;
series2.yAxis = valueAxis2;

series.columns.template.fill = am4core.color(warna5);

// Add legend
chart.legend = new am4charts.Legend();

// Add cursor
chart.cursor = new am4charts.XYCursor();
        </script>

@endsection
